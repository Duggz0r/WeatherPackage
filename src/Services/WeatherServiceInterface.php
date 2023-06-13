<?php

namespace Duggz0r\WeatherPackage\Services;

interface WeatherServiceInterface
{
    public function getWeatherForecastByIp(string $ip): array;

    public function getLocationByIp($ip): array;

    public function storeIPAddress(array $data, string $ip): void;
}
