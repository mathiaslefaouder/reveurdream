<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Theme;
use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
use App\Service\LunaryPhase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/')]
    final public function indexNoLocale(Request $request): Response
    {
        return $this->redirectToRoute('app_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/', name: 'app_index')]
    final public function index(Request $request, DreamRepository $dreamRepository, CategoryRepository $categoryRepository, ThemeRepository $themeRepository): Response
    {
        return $this->render('pages/index.html.twig', [
            'dreams' => $dreamRepository->findAllNotDraft($request->getLocale()),
            'categories' => $categoryRepository->findAll(),
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/mentions', name: 'app_mentions')]
    final public function mentions(): Response
    {
        return $this->render('pages/mentions.html.twig');
    }

    #[Route('/{_locale<%app.supported_locales%>}/contact', name: 'app_contact')]
    final public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }

    #[Route('/{_locale<%app.supported_locales%>}/why', name: 'app_why')]
    final public function why(): Response
    {
        return $this->render('pages/why.html.twig');
    }
}
