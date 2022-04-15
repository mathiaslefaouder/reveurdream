<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Theme;
use App\Repository\ThemeRepository;
use App\Service\LunaryPhase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    final public function index(): Response
    {
        return $this->render( 'pages/index.html.twig');
    }

    #[Route('/mentions', name: 'app_mentions')]
    final public function mentions(): Response
    {
        return $this->render( 'pages/mentions.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    final public function contact(): Response
    {
        return $this->render( 'pages/contact.html.twig');
    }

    #[Route('/why', name: 'app_why')]
    final public function why(): Response
    {
        return $this->render( 'pages/why.html.twig');
    }
}
