<?php

namespace Duggz0r\WeatherPackage\Services;

interface WeatherServiceInterface
{
    public function getWeatherForecastByIp(string $ip): array;
}
