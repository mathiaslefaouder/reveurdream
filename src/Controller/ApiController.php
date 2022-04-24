<?php

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/dream-data-map', name: 'api_dream_data_map')]
    final public function dataMap(DreamRepository $dreamRepository, Request $request, ThemeRepository $themeRepository, CategoryRepository $categoryRepository): Response
    {
        $dreams = $dreamRepository->dataForMap($request->getLocale());
        $category = $categoryRepository->findAll();
        $themes = $themeRepository->findAll();

        return $this->json([
            'dreams' => $dreams,
            'category' => $category,
            'themes' => $themes,
        ]);
    }

    #[Route('/dream-inc-view', name: 'api_dream_incremente_view')]
    final public function incrementeView(DreamRepository $dreamRepository, Request $request, EntityManagerInterface $entityManager): void
    {
        $dream = $dreamRepository->find($request->get('id'));
        $dream->setNumberView($dream->getNumberView() + 1);
        $entityManager->flush();
    }
}
