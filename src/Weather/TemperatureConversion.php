<?php


namespace App\Weather;

class TemperatureConversion
{
    public function kelvin_to_celsius($kelvin)
    {
        return $kelvin -273.15;
    }
}