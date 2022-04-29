<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dream;
use App\Entity\Theme;
use App\Repository\CategoryRepository;
use App\Repository\DreamRepository;
use App\Repository\ThemeRepository;
use App\Service\LocalizationService;
use App\Service\LunaryPhaseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/mydream')]
class DreamController extends AbstractController
{

    /**
     * @throws \JsonException|\Doctrine\ORM\NonUniqueResultException
     */
    #[Route('', name: 'app_dream')]
    final public function index(Request $request, EntityManagerInterface $entityManager, DreamRepository $dreamRepository, CategoryRepository $categoryRepository, SessionInterface $session, ThemeRepository $themeRepository, LocalizationService $localizationService, LunaryPhaseService $lunaryPhaseService): Response
    {
        if ($request->get('cancel')) {
            $session->set('step', null);
            $session->set('dream', null);
            return $this->redirectToRoute('app_index');
        }
        if ($session->get('dream')) {
            $dream = $dreamRepository->find($session->get('dream')->getId());
        } else {
            $dream = new Dream();
            $ip = $request->server->get('REMOTE_ADDR');
            $ip_data = $localizationService->ip_data($ip);
            $dream->setCreatedAt((new \DateTimeImmutable()))
                ->setIsDraft(true)
                ->setLang($request->getLocale())
                ->setLunaryPhase($lunaryPhaseService->phase()['phaseName'])
                ->setNumberView(0)
                ->setGps([
                    'lat' => $ip_data->lat,
                    'log' => $ip_data->lon
                ]);
        }

        // category
        if ($request->get('category')) {
            $cat = $categoryRepository->find($request->get('category'));

            if ($this->getUser()) {
                $dream->setAuthor($this->getUser());
            }
            $dream->setCategory($cat);
            $session->set('step', 'category');
        } //theme
        elseif ($request->get('theme')) {
            $dream->setTheme($themeRepository->find($request->get('theme')));
            $session->set('step', 'theme');
        } //content
        elseif ($request->get('dream_title') && $request->get('dream_story')) {
            $dream->setTitle($request->get('dream_title'))
                ->setDescription($request->get('dream_story'));
            $session->set('step', 'content');

            if ($request->get('Enregistrer')){
                $session->set('step', null);
                $session->set('dream', null);
                $entityManager->flush();
                $this->redirectToRoute('app_dream_edit', ['id' => $dream->getId()]);

            }

        } //publish
        elseif ($request->get('publish')) {
            $dream->setIsDraft(false);
            $session->set('step', null);
            $session->set('dream', null);
            $entityManager->flush();
            return $this->redirectToRoute('app_index');
        } else {
            switch ($session->get('step')) {
                case 'category':
                    $session->set('step', null);
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
        if (empty($dream->getId())) {
            $entityManager->persist($dream);
            $entityManager->flush();
        } else {
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
                    $entityManager->flush();
                    $session->set('step', null);
                    $session->set('dream', null);

                    $dreamRepository->setAllDraftExcept($this->getUser(), $dream);

                    $entityManager->flush();
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
    final public function edit(Request $request, EntityManagerInterface $entityManager, Dream $dream, DreamRepository $dreamRepository): Response
    {
        $form = $this->createFormBuilder($dream)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('isDraft', CheckboxType::class, ['required' => false])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'ico',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'DESC');
                },
            ])
            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'ico',
                'query_builder' => function (ThemeRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if (!$dream->getIsDraft()){
                $dreamRepository->setAllDraftExcept($this->getUser(), $dream, $request);
            }
            $entityManager->persist($dream);
            $entityManager->flush();
        }

        return $this->render('dream/edit.html.twig', [
            "dream" => $dream,
            'form' => $form->createView()
        ]);
    }
}
