<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dream;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mydream')]
class DreamController extends AbstractController
{
    #[Route('/step-1/{id}', name: 'app_dream_category')]
    final public function stepCategory(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, DreamRepository $dreamRepository, Dream $id = null): Response
    {
        $dream = !$id ? new Dream() : $id;

        if ($request->get('category')) {
            $dream->setCategory($categoryRepository->find($request->get('category')))
                ->setCreatedAt((new \DateTimeImmutable()))
                ->setIsDraft(true)
                ->setNumberView(0);
            if ($this->getUser()) {
                $dream->setAuthor($this->getUser());
            }
            $entityManager->persist($dream);
            $entityManager->flush();

            return $this->redirectToRoute('app_dream_theme', [
                'id' => $dream->getId()
            ]);
        }
        return $this->render('dream/step_category.html.twig',[
            'dream' => $dream
        ]);
    }

    #[Route('/step-2/{id}', name: 'app_dream_theme')]
    final public function stepTheme(Request $request, EntityManagerInterface $entityManager, Dream $dream, ThemeRepository $themeRepository): Response
    {
        if ($request->get('theme')) {
            $dream->setTheme($themeRepository->find($request->get('theme')));
            $entityManager->persist($dream);
            $entityManager->flush();


            return $this->redirectToRoute('app_dream_content', [
                'id' => $dream->getId()
            ]);
        }
        return $this->render('dream/step_theme.html.twig', [
            "dream" => $dream
        ]);
    }

    #[Route('/step-3/{id}', name: 'app_dream_content')]
    final public function stepContent(Request $request, EntityManagerInterface $entityManager, Dream $dream, ThemeRepository $themeRepository): Response
    {
        if ($request->get('dream_title') && $request->get('dream_story')) {
            $dream->setTitle($request->get('dream_title'))
                ->setDescription($request->get('dream_story'));
            $entityManager->persist($dream);
            $entityManager->flush();

            return $this->redirectToRoute('app_dream_final', [
                'id' => $dream->getId()
            ]);
        }
        return $this->render('dream/step_content.html.twig', [
            "dream" => $dream
        ]);
    }

    #[Route('/step-4/{id}', name: 'app_dream_final')]
    final public function stepFinal(Request $request, EntityManagerInterface $entityManager, Dream $dream, ThemeRepository $themeRepository): Response
    {
        if ($request->get('theme')) {
            $dream->setTheme($themeRepository->find($request->get('theme')));
            $entityManager->persist($dream);
            $entityManager->flush();


            return $this->redirectToRoute('app_index', [
                'id' => $dream->getId()
            ]);
        }
        return $this->render('dream/step_final.html.twig', [
            "dream" => $dream
        ]);
    }
}
