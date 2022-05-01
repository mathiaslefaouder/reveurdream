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
    final public function phase(): array
    {
        $conf = [

            'lang' => 'fr',
            'month' => (int)(date('n')),
            'year' => (int)(date('Y')),
            'size' => 24,
            'shadeColor' => "rgb(6,14,37)",
            'lightColor' => "rgb(85,192,243)",
            'texturize' => false,
        ];

        try {
            $response = $this->client->request('GET', 'https://www.icalendar37.net/lunar/api/', [
                'query' => $conf,
                'verify_host' => false,
            ]);
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
        $data = json_decode($response->getContent(), true);

        return $data['phase'][(date('j'))];
    }
}
