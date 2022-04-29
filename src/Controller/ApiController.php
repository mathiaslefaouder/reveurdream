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

        return $this->json([
            'dreams' => array_values($groupes),
            'category' => $category,
            'themes' => $themes,
        ]);
    }

    #[Route('/dream-inc-view', name: 'api_dream_incremente_view')]
    final public function incrementeView(DreamRepository $dreamRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $ids = explode('-', $request->get('id'));
        array_pop($ids);
        foreach ($ids as $id){
            $dream = $dreamRepository->find($id);
            $dream->setNumberView($dream->getNumberView() + 1);
        }
        $entityManager->flush();
        return new Response('ok');
    }
}
