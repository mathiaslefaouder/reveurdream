<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dream;
use App\Entity\Theme;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\DreamRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\DreamService;
use App\Service\LunaryPhaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/{_locale<%app.supported_locales%>}')]
class SecurityController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    final public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            if (!empty($session->get('dream'))) {
                 $dream = new Dream();
                 $theme = $entityManager->getRepository(Theme::class)->find($session->get('dream')->getTheme()->getId());
                 $category = $entityManager->getRepository(Category::class)->find($session->get('dream')->getCategory()->getId());
                $dream->setAuthor($user)
                    ->setTitle($session->get('dream')->getTitle())
                    ->setDescription($session->get('dream')->getDescription())
                    ->setCreatedAt($session->get('dream')->getCreatedAt())
                    ->setTheme($theme)
                    ->setLunaryPhase($session->get('dream')->getLunaryPhase())
                    ->setCategory($category)
                    ->setPhaseName($session->get('dream')->getPhaseName())
                    ->setGps($session->get('dream')->getGps())
                    ->setLang($session->get('dream')->getLang())
                    ->setNumberView(0)
                    ->setIsDraft(false);

                $entityManager->persist($dream);
                $entityManager->flush();

                $session->set('step', null);
            }

            if ($request->getLocale() === 'fr') {
                $subject = 'Confirmation de votre inscription reveurdream.com';
                $template = 'mails/confirmation_inscription/fr.html.twig';
            }elseif ($request->getLocale() === 'es') {
                $subject = 'Confirmación de su inscripción reveurdream.com';
                $template = 'mails/confirmation_inscription/es.html.twig';
            }else{
                $subject = 'Confirmation of your registration reveurdream.com';
                $template = 'mails/confirmation_inscription/en.html.twig';
            }
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('contact@reveurdream.com', 'Reveur Dream'))
                    ->to($user->getEmail())
                    ->subject($subject)
                    ->htmlTemplate($template)
                ->context(['user' => $user])
            );

            return $this->redirectToRoute('app_confirm');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    final public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_confirm');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_valide');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_valide');
    }

    #[Route('/login', name: 'app_login')]
    final public function login(AuthenticationUtils $authenticationUtils, DreamService $dreamService, Request $request): Response
    {
        $dreams = $dreamService->getData();
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'dreams' => array_values($dreams),
        ]);
    }

    #[Route('/confirm', name: 'app_confirm')]
    final public function confirm(SessionInterface $session): Response
    {
        if (!empty($session->get('dream'))) {
            $session->set('dream', null);
            return $this->render('dream/step_final_new.html.twig');
        }

        return $this->render('security/valide.html.twig');
    }

}
