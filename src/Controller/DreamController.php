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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/mydream')]
class DreamController extends AbstractController
{

    /**
     * @throws \JsonException
     */
    #[Route('', name: 'app_dream')]
    final public function index(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, SessionInterface $session, ThemeRepository $themeRepository): Response
    {

        if ($session->get('dream')) {
            $dream = $session->get('dream');
        } else {
            $dream = new Dream();
            $ip = $request->server->get('REMOTE_ADDR');
            $ip_data = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip), false, 512, JSON_THROW_ON_ERROR);

            $dream->setCreatedAt((new \DateTimeImmutable()))
                ->setIsDraft(true)
                ->setNumberView(0)
                ->setGps([
                    'lat' => $ip_data->geoplugin_latitude,
                    'log' => $ip_data->geoplugin_longitude
                ]);
        }
        // category
        if ($request->get('category')) {
               $dream->setCategory($categoryRepository->find($request->get('category')));

            if ($this->getUser()) {
                $dream->setAuthor($this->getUser());
            }

            $session->set('step', 'category');
        }
        //theme
        elseif ($request->get('theme')) {
            $dream->setTheme($themeRepository->find($request->get('theme')));
            $session->set('step', 'theme');
        }
        //content
        elseif ($request->get('dream_title') && $request->get('dream_story')) {
            $dream->setTitle($request->get('dream_title'))
                ->setDescription($request->get('dream_story'));
            $session->set('step', 'content');

        }
        //publish
        elseif ($request->get('publish')) {
            $dream->setIsDraft(false);
            $session->set('step', null);
            $session->set('dream', null);
            return $this->redirectToRoute('app_index');
        }
        else{
            switch ($session->get('step')) {
                case 'category':
                    $session->set('step', 'null');
                    return $this->render('dream/step_category.html.twig', [
                        'dream' => $dream,
                        'categories' => $categoryRepository->findBy([], ['name' => 'DESC'])
                    ]);
                case 'theme':
                    $session->set('step', 'category');
                    return $this->render('dream/step_theme.html.twig', [
                        'dream' => $dream,
                        'themes' => $themeRepository->findBy([], ['name' => 'ASC'])
                    ]);
                case 'content':
                    $session->set('step', 'theme');
                    return $this->render('dream/step_content.html.twig', [
                        'dream' => $dream,
                    ]);
            }
        }

        if (!$dream->getId()){
            $entityManager->persist($dream);
        }else{
            $entityManager->flush();
        }

        $session->set('dream', $dream);
        switch ($session->get('step')) {
            case 'category':
                return $this->render('dream/step_theme.html.twig', [
                    'dream' => $dream,
                    'themes' => $themeRepository->findBy([], ['name' => 'ASC'])
                ]);
            case 'theme':
                return $this->render('dream/step_content.html.twig', [
                    'dream' => $dream,
                ]);
            case 'content':
                if ($this->getUser()) {
                    $dream->setIsDraft(false);
                    return $this->render('dream/step_final.html.twig', [
                        'dream' => $dream,
                    ]);
                }

                return $this->render('dream/step_register.html.twig', [
                    'dream' => $dream,
                ]);
        }
        return $this->render('dream/step_category.html.twig', [
            'dream' => $dream,
            'categories' => $categoryRepository->findBy([], ['name' => 'DESC'])
        ]);
    }

    #[Route('/edit/{id}', name: 'app_dream_edit')]
    final public function edit(Request $request, EntityManagerInterface $entityManager, Dream $dream): Response
    {

        return $this->render('dream/edit.html.twig', [
            "dream" => $dream
        ]);
    }
}
