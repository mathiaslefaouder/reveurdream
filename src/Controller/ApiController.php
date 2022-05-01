<?php

namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
use App\Service\DreamService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\TwigConfig;

class ApiController extends AbstractController
{
    #[Route('/dream-data-map', name: 'api_dream_data_map')]
    final public function dataMap(Request $request, ThemeRepository $themeRepository, CategoryRepository $categoryRepository, DreamService $dreamService): Response
    {
        $dreams = $dreamService->getData($request->getLocale());
        $category = $categoryRepository->findAll();
        $themes = $themeRepository->findAll();


        return $this->json([
            'dreams' => array_values($dreams),
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

    #[Route('/globe', name: 'globe')]
    final public function globe(DreamService $dreamService, Request $request): Response
    {
        $dreams = $dreamService->getData($request->getLocale());

        $response = new Response($this->render('pages/_globe.html.twig',[
            'dreams' => array_values($dreams)
        ]));

        $response->setSharedMaxAge(3600);

        return $response;
    }

    #[Route('/admin/http-cache/{uri<.*>}', methods: ['PURGE'])]
    public function purgeHttpCache(KernelInterface $kernel, Request $request, string $uri, StoreInterface $store): Response
    {
        if ('prod' === $kernel->getEnvironment()) {
            return new Response('KO', 400);
        }

        $store->purge($request->getSchemeAndHttpHost() . '/' . $uri);

        return new Response('Done');
    }
}
