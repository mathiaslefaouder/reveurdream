<?php

namespace App\Service;

use App\Repository\DreamRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class DreamService
{
    private DreamRepository $dreamRepository;
    private Security $tokenStorage;

    public function __construct(DreamRepository $dreamRepository, Security $tokenStorage)
    {
        $this->dreamRepository = $dreamRepository;
        $this->tokenStorage = $tokenStorage;
    }

    function getData(): array
    {
        $dreams = $this->dreamRepository->dataForMap();

        $groupes = [];
        foreach ($dreams as $dream) {
            $dream['coord'] = $dream['gps']['lat'] . $dream['gps']['log'];
            $groupes[$dream['coord']]['lat'] = $dream['gps']['lat'];
            $groupes[$dream['coord']]['lng'] = $dream['gps']['log'];
            empty($groupes[$dream['coord']]['id']) ? $groupes[$dream['coord']]['id'] = $dream['id'] . '-' : $groupes[$dream['coord']]['id'] .= $dream['id'] . '-';
            $groupes[$dream['coord']]['theme_short'][] = $dream['theme_short'];
            $groupes[$dream['coord']]['category'][] = strtolower($dream['category']);

            if (!empty($this->tokenStorage->getToken()) && $this->tokenStorage->getToken()->getUser()->getId() === $dream['author']) {
                $dream['is_author'] = true;
            } else {
                $dream['is_author'] = false;
            }
            $groupes[$dream['coord']]['dreams'][] = $dream;

        }

        return $groupes;

    }

}
