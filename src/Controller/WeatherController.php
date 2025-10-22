<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Location;
use App\Repository\MeasurementRepository;
use App\Repository\LocationRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class WeatherController extends AbstractController
{
//     #[Route('/weather/{id}', name: 'app_weather', requirements: ['id' => '\d+'])]
//         public function city(Location $location, MeasurementRepository $repository): Response
//         {
//             $measurements = $repository->findByLocation($location);
// //     dd($location, $measurements);
//             return $this->render('weather/city.html.twig', [
//                 'location' => $location,
//                 'measurements' => $measurements,
//             ]);
//         }
        #[Route('/weather/{cityAndCountry}', name: 'app_weather')]
    public function city(
        string $cityAndCountry,
        LocationRepository $locationRepository,
        MeasurementRepository $repository
    ): Response {
        $parts = explode(',', $cityAndCountry);
        $city = $parts[0];
        $country = $parts[1] ?? null;
        if ($country) {
            $location = $locationRepository->findOneBy([
                'city' => $city,
                'country' => $country,
            ]);
        } else {
            $location = $locationRepository->findOneBy(['city' => $city]);
        }

        if (!$location) {
            throw new NotFoundHttpException(sprintf('Location "%s" not found.', $cityAndCountry));
        }
        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
    
}
