<?php

use App\DoctrineType\AbstractEnumType;

class VehicleType extends AbstractEnumType
{
    public const NAME = 'vehicle';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string 
    {
        return VehicleEnum::class;
    }
}