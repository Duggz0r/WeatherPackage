<?php

namespace Duggz0r\WeatherPackage\Drivers;

interface WeatherDriverInterface
{
    public function getWeatherForecast(string $latitude, string $longitude): array;

    public function transformData(array $rawData): array;
}
