<?php


namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FacebookAuthenticator extends AbstractAuthenticator
{
    private ClientRegistry  $clientRegistry;
    private UserRepository $userRepository;
    private UrlGeneratorInterface $urlGenerator;
    private RouterInterface $router;
    private EntityManagerInterface $entityManager;


    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return 'connect_facebook_check' === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->getFacebookClient();

        $facebookUser = $this->getFacebookClient()->fetchUserFromToken($client->getAccessToken());

        return new Passport(
            new UserBadge($facebookUser->getEmail(), function ($userIdentifier) use ($request, $facebookUser) {
                $user = $this->userRepository->findOneByEmail($userIdentifier);
                if ($user) {
                    $user->setFacebookId($facebookUser->getId());
                } else {
                    // create new user
                }
                $this->entityManager->flush();

                return $user;
            }),
            new CustomCredentials(function ($credentials, User $user) {
                return $credentials->getEmail() === $user->getEmail();
            }, $facebookUser),
        );
    }

    public function createAuthenticatedToken(Passport $passport, string $firewallName): UsernamePasswordToken
    {
        return new UsernamePasswordToken($passport->getUser(), $firewallName);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('app_index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->isXmlHttpRequest()) {
            // if AJAX login
            $response = new JsonResponse([
                'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
            ], Response::HTTP_UNAUTHORIZED);
        } else {
            // if form login
            // set authentication exception to session
            if ($request->hasSession()) {
                $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            }
            $response = new RedirectResponse($this->router->generate('app_login'));
        }

        return $response;
    }

    private function getFacebookClient()
    {
        return $this->clientRegistry->getClient('facebook');
    }
}