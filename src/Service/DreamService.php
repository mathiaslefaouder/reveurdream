<?php

namespace App\Service;

use App\Repository\DreamRepository;

class DreamService
{
    private DreamRepository $dreamRepository;

    public function __construct(DreamRepository $dreamRepository)
    {
        $this->dreamRepository = $dreamRepository;
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
            $groupes[$dream['coord']]['dreams'][] = $dream;
        }

        return $groupes;

    }

}
