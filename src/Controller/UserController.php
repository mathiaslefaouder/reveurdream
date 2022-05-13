<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DreamRepository;
use App\Repository\UserRepository;
use App\Service\LunaryPhaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/{_locale<%app.supported_locales%>}')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    final public function index(DreamRepository $dreamRepository, Request $request, EntityManagerInterface $entityManager, LunaryPhaseService $lunaryPhaseService, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $pwd = $userRepository->find($this->getUser()->getId())->getPassword();
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getPassword() === null) {
                $form->getData()->setPassword($pwd);
            } else {
                $form->getData()->getPassword()->setPassword($userPasswordHasher->hashPassword($form->getData(), $form->getData()->getPassword()));
            }
            $entityManager->persist($form->getData());
            $entityManager->flush();

        }

        $dreams = $dreamRepository->findBy(['author' => $this->getUser()], ['id' => 'DESC']);
        $date = new \DateTime(' -1 day');
        foreach ($dreams as $dream) {
            if (!$dream->getIsDraft() && $dream->getCreatedAt() < $date) {
                $dream->setIsDraft(true);
                $entityManager->flush();
            }
        }
        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'dreams' => $dreams,
        ]);
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    #[Route('/user/delete', name: 'app_user_delete')]
    final public function delete(UserRepository $userRepository, EntityManagerInterface $entityManager, DreamRepository $dreamRepository, SessionInterface $session, TokenStorageInterface $tokenStorage): RedirectResponse
    {
        $currentUserId = $this->getUser()->getId();
        $dreams = $dreamRepository->findBy(['author' => $currentUserId]);
        foreach ($dreams as $dream){
            $dream->setAuthor(null);
            $dream->setIsDraft(true);
        }
        $entityManager->flush();
        $tokenStorage->setToken(null);
        $session->invalidate();
        $userRepository->remove($userRepository->find($currentUserId));
        $session->start();

        $this->addFlash('success', 'Votre compte a bien été supprimé');
        return $this->redirectToRoute('app_index');
    }
}
