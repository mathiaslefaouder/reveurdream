<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Entity\Dream;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use App\Entity\User;

class LoginListener
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    final public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        // Get the User entity.
        $session = $event->getRequest()->getSession();
        $user = $event->getAuthenticationToken()->getUser();

        if (!empty($session->get('dream')) && $session->get('dream')->getDescription() !== null) {
            $dream = new Dream();
            $theme = $this->em->getRepository(Theme::class)->find($session->get('dream')->getTheme()->getId());
            $category =  $this->em->getRepository(Category::class)->find($session->get('dream')->getCategory()->getId());
            $dream->setAuthor($user)
                ->setTitle($session->get('dream')->getTitle())
                ->setDescription($session->get('dream')->getDescription())
                ->setCreatedAt($session->get('dream')->getCreatedAt())
                ->setTheme($theme)
                ->setLunaryPhase($session->get('dream')->getLunaryPhase())
                ->setCategory($category)
                ->setPhaseName($session->get('dream')->getPhaseName())
                ->setGps($session->get('dream')->getGps())
                ->setLang($session->get('dream')->getLang())
                ->setNumberView(0)
                ->setIsDraft(false);

            $this->em->persist($dream);
            $this->em->flush();

            $session->set('step', null);
        }

    }
}