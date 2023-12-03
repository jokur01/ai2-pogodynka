<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $measurements = $util->getWeatherForCountryAndCity($country, $city);

        $data = [
            'measurements' => array_map(fn(Measurement $m) => [
                'city' => $m->getLocation()->getCity(),
                'country' => $m->getLocation()->getCountry(),
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
                'fahrenheit' => $m->getFahrenheit()
        ], $measurements),];



        if($format != 'json'){
            if(!$twig){
                $csvData = null;
                foreach ($data as $inp){
                    foreach ($inp as $fields){
                        $csvData .= implode(',', [
                            $fields['city'],
                            $fields['country'],
                            $fields['date'],
                            $fields['celsius'],
                            $fields['fahrenheit']
                        ]).PHP_EOL;
                        return new Response($csvData);
                    }
                }
            }
            return $this->render('weather_api/index.csv.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements
                ]);
        }
        if(!$twig) {
            return $this->json([$data]);
        }
        return $this->render('weather_api/index.json.twig', [
            'city' => $city,
            'country' => $country,
            'measurements' => $measurements
        ]);
    }
}