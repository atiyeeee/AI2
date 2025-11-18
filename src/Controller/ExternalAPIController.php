<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;

final class ExternalAPIController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/externalapi', name: 'app_external_api')]
    public function index(Request $request): Response
    {
        $weatherData = null;
        $latitude = trim($request->query->get('latitude'));
        $longitude = trim($request->query->get('longitude'));

        //dd($latitude, $longitude);

        if ($latitude !== null && $longitude !== null && $latitude !== '' && $longitude !== '') {
            $apiUrl = sprintf(
                'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&daily=temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=UTC',
                $latitude,
                $longitude
            );

            try {
                $response = $this->client->request('GET', $apiUrl);

                if ($response->getStatusCode() === 200) {
                    $weatherData = $response->toArray();
                    //dump($weatherData);
                } else {
                    $this->addFlash('error', 'Błąd API, status: ' . $response->getStatusCode());
                }
            } catch (\Throwable $e) {
                $this->addFlash('error', 'Błąd przy pobieraniu danych: ' . $e->getMessage());
            }
        }

        return $this->render('external_api/index.html.twig', [
            'weather_data' => $weatherData,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
