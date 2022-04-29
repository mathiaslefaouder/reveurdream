<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Theme;
use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
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
    final public function index(Request $request, DreamRepository $dreamRepository, CategoryRepository $categoryRepository, ThemeRepository $themeRepository, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        $dreams = $dreamRepository->dataForMap($request->getLocale());
        $groupes = [];
        foreach ($dreams as $dream){
            $dream['coord'] = $dream['gps']['lat'].$dream['gps']['log'];
            $groupes[$dream['coord']]['lat'] = $dream['gps']['lat'];
            $groupes[$dream['coord']]['lng'] = $dream['gps']['log'];
            empty($groupes[$dream['coord']]['id']) ? $groupes[$dream['coord']]['id'] = $dream['id'].'-':  $groupes[$dream['coord']]['id'] .= $dream['id'].'-';
            $groupes[$dream['coord']]['theme_short'][] = $dream['theme_short'];
            $groupes[$dream['coord']]['category'][] = strtolower($dream['category']);
            $groupes[$dream['coord']]['dreams'][] = $dream;
            if (count($groupes[$dream['coord']]['dreams']) ==  1){
                $groupes[$dream['coord']]['pin'] = '/img/epingle-'.$dream['theme_short'].'.png';
            }else{
                $groupes[$dream['coord']]['pin'] = '/img/epingle_bleufonce.png';
            }
        }

        return $this->render('pages/index.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => array_values($groupes)
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/mentions', name: 'app_mentions')]
    final public function mentions(): Response
    {
        return $this->render('pages/mentions.html.twig');
    }

    #[Route('/{_locale<%app.supported_locales%>}/contact', name: 'app_contact')]
    final public function contact(DreamRepository $dreamRepository, Request $request): Response
    {
        return $this->render('pages/contact.html.twig',[
            'dreams' => $dreamRepository->dataForMap($request->getLocale())
        ]);
    }

    #[Route('/{_locale<%app.supported_locales%>}/why', name: 'app_why')]
    final public function why(DreamRepository $dreamRepository, Request $request, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        return $this->render('pages/why.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => $dreamRepository->dataForMap($request->getLocale())
        ]);
    }


    #[Route('/{_locale<%app.supported_locales%>}/valide', name: 'app_valide')]
    final public function valide(DreamRepository $dreamRepository, Request $request, LunaryPhaseService $lunaryPhaseService, LocalizationService $localizationService): Response
    {
        return $this->render('security/valide.html.twig', [
            'lunaryPhase' => $lunaryPhaseService->phase(),
            'hemisphere' => $localizationService->getHemisphere($request),
            'dreams' => $dreamRepository->dataForMap($request->getLocale())
        ]);
    }
}
