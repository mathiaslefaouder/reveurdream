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
        $count = 0;
        foreach ($dreams as $dream) {
            $count++;
            $dream['coord'] = $dream['gps']['lat'] . $dream['gps']['log'];
            $groupes[$dream['coord']]['lat'] = $dream['gps']['lat'];
            $groupes[$dream['coord']]['lng'] = $dream['gps']['log'];
            empty($groupes[$dream['coord']]['id']) ? $groupes[$dream['coord']]['id'] = $dream['id'] . '-' : $groupes[$dream['coord']]['id'] .= $dream['id'] . '-';
            $groupes[$dream['coord']]['theme_short'][] = $dream['theme_short'];
            $groupes[$dream['coord']]['category'][] = strtolower($dream['category']);
            $groupes[$dream['coord']]['dreams'][] = $dream;
            if (count($groupes[$dream['coord']]['dreams']) == 1) {
//                $groupes[$dream['coord']]['pin'] = '/img/epingle-' . $dream['theme_short'] . '.png';
                $groupes[$dream['coord']]['pin'] =
                    '<svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 595.3 841.9" style="enable-background:new 0 0 595.3 841.9;" xml:space="preserve"> <style type="text/css"> .st0{fill:#1C1F3C;} .st1{fill:none;stroke:#FFFFFF;stroke-width:4;stroke-miterlimit:10;} .st2{fill:#FFFFFF;} </style> <path class="st0" d="M343.6,407c0,40-46.1,73.1-46.1,73.1s-46.1-33-46.1-73.1c0-25.5,20.6-46.1,46.1-46.1S343.6,381.5,343.6,407z"/> <circle class="st1" cx="297.5" cy="407" r="34.4"/> <g><text x="284" y="428" font-family="Verdana" font-size="55" fill="white"> ' . $count . ' </text> </g> </svg> ';


            } else {
                $groupes[$dream['coord']]['pin'] =
                    '<svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 595.3 841.9" style="enable-background:new 0 0 595.3 841.9;" xml:space="preserve"> <style type="text/css"> .st0{fill:#1C1F3C;} .st1{fill:none;stroke:#FFFFFF;stroke-width:4;stroke-miterlimit:10;} .st2{fill:#FFFFFF;} </style> <path class="st0" d="M343.6,407c0,40-46.1,73.1-46.1,73.1s-46.1-33-46.1-73.1c0-25.5,20.6-46.1,46.1-46.1S343.6,381.5,343.6,407z"/> <circle class="st1" cx="297.5" cy="407" r="34.4"/> <g><text x="284" y="428" font-family="Verdana" font-size="55" fill="white"> ' . $count . ' </text> </g> </svg> ';
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
        foreach ($ids as $id) {
            $dream = $dreamRepository->find($id);
            $dream->setNumberView($dream->getNumberView() + 1);
        }
        $entityManager->flush();
        return new Response('ok');
    }
}
