<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class VehicleController extends AbstractController
{
    #[Route('/vehicles', name: 'app_vehicle', methods: ['GET'])]
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $vehicles = $vehicleRepository->findAll();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/VehicleController.php',
            'vehicles' => $vehicles
        ]);
    }

    #[Route('/vehicle/show/{id}', name: 'vehicle_show', methods: ['GET'])]
    public function show(VehicleRepository $vehicleRepository, int $id): Response
    {
        $vehicle = $vehicleRepository->find($id);

        if (!$vehicle){
            return $this->json([
                'error' => 'No vehicle found'
            ], 400);
        }
        return $this->json([
            'message' => [
                'id' => $vehicle->getId(),
                'type' => $vehicle->getType(),
                'msrp' => $vehicle->getMsrp(),
                'year' => $vehicle->getYear(),
                'model' => $vehicle->getModel(),
                'miles' => $vehicle->getMiles(),
                'vin' => $vehicle->getVin()
            ]
        ], 200);
    }

    #[Route('/vehicle/create', name: 'vehicle_create', methods: ['POST'])]
    public function create(ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator): Response
    {
        $manager = $doctrine->getManager();
        $vehicle = new Vehicle();
        $date = new \DateTime('@'.strtotime('now'));
        $vehicle->setDateAdded($date);
        $type = $vehicle->setType($request->request->get('type'));
        $msrp = $vehicle->setMsrp($request->request->get('msrp'));
        $year = $vehicle->setYear($request->request->get('year'));
        $make = $vehicle->setMake($request->request->get('make'));
        $model = $vehicle->setModel($request->request->get('model'));
        $miles = $vehicle->setMiles($request->request->get('miles'));
        $vin = $vehicle->setVin($request->request->get('vin'));

       // dd($type);
        $input = [
            'type' => $type,
            'msrp' => $msrp,
            'year' => $year, 
            'make' => $make,
            'model' => $model,
            'miles' => $miles,
            'vin' => $vin
        ];

        $constraints = new Assert\Collection([
            'msrp' => [new Assert\NotBlank, new Assert\Positive],
            'year' => [new Assert\NotBlank, new Assert\DateTime('Y')],
            'make' => [new Assert\NotBlank],
            'model' => [new Assert\NotBlank],
            'miles' => [new Assert\NotBlank, new Assert\Positive],
            'vin' => [new Assert\NotBlank],
        ]);

        $errors = $validator->validate($input, $constraints);

        if (count($errors) > 0){
            return $this->json([
                'errors' => $errors
            ], 400);
        }
        

        $manager->persist($vehicle);
        $manager->flush();

        return $this->json([
            'message' => 'Success!'
        ], 200);
    }

    #[Route('vehicle/edit/{id}', name: 'vehicle_edit', methods: ['PATCH', 'POST'])]
    public function edit(VehicleRepository $vehicleRepository, ManagerRegistry $doctrine, Request $request, ValidatorInterface $validator, int $id): Response
    {
        $manager = $doctrine->getManager();

        $vehicle = $vehicleRepository->find($id);

        if (!$vehicle){
            return $this->json([
                'error' => 'This vehicle was not found.'
            ]);
        }

        $type = $vehicle->setType($request->request->get('type'));
        $msrp = $vehicle->setMsrp($request->request->get('msrp'));
        $year = $vehicle->setYear($request->request->get('year'));
        $make = $vehicle->setMake($request->request->get('make'));
        $model = $vehicle->setModel($request->request->get('model'));
        $miles = $vehicle->setMiles($request->request->get('miles'));
        $vin = $vehicle->setVin($request->request->get('vin'));

        
        $input = [
            'type' => $type,
            'msrp' => $msrp,
            'year' => $year, 
            'make' => $make,
            'model' => $model,
            'miles' => $miles,
            'vin' => $vin
        ];

        $constraints = new Assert\Collection([
            'msrp' => [new Assert\NotBlank, new Assert\Positive],
            'year' => [new Assert\NotBlank, new Assert\DateTime('Y')],
            'make' => [new Assert\NotBlank],
            'model' => [new Assert\NotBlank],
            'miles' => [new Assert\NotBlank, new Assert\Positive],
            'vin' => [new Assert\NotBlank],
        ]);

        $errors = $validator->validate($input, $constraints);

        if (count($errors) > 0){
            return $this->json([
                'errors' => $errors
            ], 400);
        }

        $manager->flush();

        return $this->json([
            'message' => 'Success!'
        ], 200);
    }

    #[Route('vehicle/delete/{id}', name: 'vehicle_delete', methods: ['DELETE', 'POST'])]
    public function delete(VehicleRepository $vehicleRepository, ManagerRegistry $doctrine, int $id)
    {
        $manager = $doctrine->getManager();

        $vehicle = $vehicleRepository->find($id);

        if (!$vehicle){
            return $this->json([
                'error' => 'This vehicle was not found.'
            ]);
        }

        $manager->remove($vehicle);
        $manager->flush();

        return $this->json([
            'message' => 'Successfully deleted!'
        ]);
    }
}
