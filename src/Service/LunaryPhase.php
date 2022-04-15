<?php
namespace App\Service;

use DateTime;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class LunaryPhase
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     */
    final public function phase(): ResponseInterface
    {
        $d = date('m');
        return $this->client->request('GET', 'https://www.icalendar37.net/lunar/api/'.$d);
    }
}
