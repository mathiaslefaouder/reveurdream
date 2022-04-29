<?php
namespace App\Service;

use App\Repository\CountryRepository;

class  LocalizationService {

    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    function ip_data($ip){
        $ip = "77.131.18.20";
        $url = "http://ip-api.com/json/".$ip;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, false, 512, JSON_THROW_ON_ERROR);
    }

    function getHemisphere($request){
        $ip_data = $this->ip_data($request->server->get('REMOTE_ADDR'));
        $country = $this->countryRepository->findOneBy(['code' => $ip_data->countryCode]);

       $hemisphere =  $country !== null ? $country->getHemisphere():  'northern';
        return match ($request->getLocale()) {
            'fr' => $hemisphere === 'southern' ? 'l’Hémisphère Sud' : 'l’Hémisphère Nord',
            'es' => $hemisphere === 'southern' ? 'Hemisferio Sur' : 'Hemisferio Norte',
            default => $hemisphere === 'southern' ? 'Southern Hemisphere' : 'Northern Hemisphere',
        };
    }
}