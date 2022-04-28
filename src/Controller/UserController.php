<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\DreamRepository;
use App\Repository\UserRepository;
use App\Service\LunaryPhaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    final public function index(DreamRepository $dreamRepository, Request  $request, EntityManagerInterface $entityManager, LunaryPhaseService $lunaryPhaseService,UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $pwd = $userRepository->find($this->getUser()->getId())->getPassword();
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()->getPassword() === null) {
                $form->getData()->setPassword($pwd);
            } else {
                $form->getData()->getPassword()->setPassword($userPasswordHasher->hashPassword($form->getData(), $form->getData()->getPassword()));
            }
            $entityManager->persist( $form->getData());
            $entityManager->flush();

        }
        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'dreams' => $dreamRepository->findBy(['author' => $this->getUser()], ['id' => 'DESC'])
        ]);
    }
}
