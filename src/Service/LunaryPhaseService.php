<?php

namespace App\Service;

use DateTime;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class LunaryPhaseService
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    final public function phase($lang): array
    {
        $conf = [

            'lang' => $lang,
            'month' => (int)(date('n')),
            'year' => (int)(date('Y')),
            'size' => 24,
            'shadeColor' => "rgb(6,14,37)",
            'lightColor' => "rgb(85,192,243)",
            'texturize' => false,
        ];

            $response = $this->client->request('GET', 'https://www.icalendar37.net/lunar/api/', [
                'query' => $conf,
                'verify_host' => false,
            ]);
            if (200 == $response->getStatusCode()){
                $data = json_decode($response->getContent(), true);
                $data = $data['phase'][(date('j'))];
            }else{
                $data =   ["phaseName" => "Croissant",
                    "isPhaseLimit" => false,
                    "lighting" => 1.5438248430572,
                    "svg" => '<svg width="24" height="24" viewBox="0 0 100 100"><g><circle cx="50" cy="50" r="49" stroke="none"  fill="rgb(6,14,37)"/><path d="M 50 1 A 49,49 0 0,1 49,99 A 48.02,49 0 1,0 50,1" stroke-width="0" stroke="none" fill="rgb(85,192,243)" /><a xlink:href="https://www.icalendar37.net/lunar/app/" rel="noopener noreferrer" target="_blank"><circle cx="50" cy="50" r="49" style="pointer-events:all;cursor:pointer" stroke-width="0"   fill="transparent" /></a></g></svg>',
                    "svgMini" => false,
                    "timeEvent" => false,
                    "dis" => 405272.2379007,
                    "dayWeek" => 3,
                    "npWidget" => "Croissant (1%)"];

            }


        return $data;
    }
}
