<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $car_types = ['sedan', 'truck', 'suv'];
        $car_model = ['toyota', 'tesla', 'honda', 'nissan', 'dodge'];
        $car_vin = 'V' . mt_rand(1000, 9999);
        for ($i = 0; $i < 10; $i++){
            $vehicle = new Vehicle();
            $date = new \DateTime('@'.strtotime('now'));
            $vehicle->setDateAdded($date);
            $vehicle->setType($car_types[rand(0, 2)]);
            $vehicle->setMsrp(mt_rand(10000, 69999));
            $vehicle->setYear(mt_rand(1999, 2022));
            $vehicle->setModel($car_model[mt_rand(0, 4)]);
            $vehicle->setMiles(mt_rand(0, 50000));
            $vehicle->setVin($car_vin);
            $vehicle->setDeleted(mt_rand(0, 1));
        }

        $manager->flush();
    }
}
