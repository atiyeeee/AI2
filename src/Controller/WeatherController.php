<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[Route('/weather')]
final class WeatherController extends AbstractController
{

    #[Route('/{cityAndCountry}', name: 'app_weather', requirements: ['cityAndCountry' => '.+'])]
    public function city(
        string $cityAndCountry,
        LocationRepository $locationRepository,
        MeasurementRepository $measurementRepository
    ): Response {
        $parts = explode(',', $cityAndCountry);
        $city = $parts[0];
        $country = $parts[1] ?? null;

        $location = $country
            ? $locationRepository->findOneBy(['city' => $city, 'country' => $country])
            : $locationRepository->findOneBy(['city' => $city]);

        if (!$location) {
            throw new NotFoundHttpException(sprintf('Location "%s" not found.', $cityAndCountry));
        }

        $measurements = $measurementRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

    #[Route('/', name: 'app_weather_index', methods: ['GET'])]
    public function index(LocationRepository $locationRepository): Response
    {
        $locations = $locationRepository->findAll();

        return $this->render('weather/index.html.twig', [
            'locations' => $locations,
        ]);
    }
}
