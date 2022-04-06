<?php
namespace App\Entity;

enum VehicleType: string
{
    case UsedCar = 'used';
    case NewCar = 'new';
}