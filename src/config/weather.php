<?php

use Duggz0r\WeatherPackage\Drivers\OpenWeatherMapDriver;
use Duggz0r\WeatherPackage\Drivers\WeatherBitDriver;

return [
    'driver' => env('WEATHER_DRIVER', 'openweathermap'),
    'openweathermap' => [
        'api_key' => env('OPENWEATHERMAP_API_KEY'),
        'driver' => OpenWeatherMapDriver::class,
    ],
    'weatherbit' => [
        'api_key' => env('WEATHERBIT_API_KEY'),
        'driver' => WeatherBitDriver::class,
    ],
];
