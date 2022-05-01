<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Theme;
use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
use App\Service\DreamService;
use App\Service\LocalizationService;
use App\Service\LunaryPhaseService;
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
    final public function index(Request $request, DreamService $dreamService, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        $dreams = $dreamService->getData($request->getLocale());

        return $this->render('pages/index.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => array_values($dreams)
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/mentions', name: 'app_mentions')]
    final public function mentions(): Response
    {
        return $this->render('pages/mentions.html.twig');
    }

    #[Route('/{_locale<%app.supported_locales%>}/contact', name: 'app_contact')]
    final public function contact(DreamService $dreamService, Request $request): Response
    {
        $dreams = $dreamService->getData($request->getLocale());

        return $this->render('pages/contact.html.twig', [
            'dreams' => array_values($dreams)
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/why', name: 'app_why')]
    final public function why(Request $request, DreamService $dreamService, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        $dreams = $dreamService->getData($request->getLocale());

        return $this->render('pages/why.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => array_values($dreams)
        ]);
    }


    #[Route('/{_locale<%app.supported_locales%>}/valide', name: 'app_valide')]
    final public function valide(DreamService $dreamService, Request $request, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        $dreams = $dreamService->getData($request->getLocale());

        return $this->render('security/valide.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => array_values($dreams)
        ]);
    }
}
