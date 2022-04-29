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
        return json_decode(file_get_contents("http://ip-api.com/json/" . $ip), false, 512, JSON_THROW_ON_ERROR);
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