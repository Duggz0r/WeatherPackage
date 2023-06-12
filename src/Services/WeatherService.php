<?php

namespace Duggz0r\WeatherPackage\Services;

use Duggz0r\WeatherPackage\Drivers\OpenWeatherMapDriver;
use Duggz0r\WeatherPackage\Drivers\WeatherBitDriver;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class WeatherService implements WeatherServiceInterface
{
    public function getWeatherForecastByIp($ip): array
    {
        $configDriver = config('weather.driver');
        $location = $this->getLocationByIp($ip);

        if ($location['status'] === 'fail') {
            throw new \Exception('Invalid location');
        }

        // Check if the weather forecast is already cached
        $cacheKey = $configDriver . '_weather_forecast_' . $location['lat'] . '_' . $location['lon'];
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Fetch the weather forecast
        $driver = App::make(config('weather.' . $configDriver . '.driver'));

        $forecast = $driver->getWeatherForecast($location['lat'], $location['lon']);

        // Cache the forecast for 1 hour
        Cache::put($cacheKey, $forecast, 60);

        return $forecast;
    }

    private function getLocationByIp($ip): array
    {
        $response = (new Client())->get('http://ip-api.com/json/' . $ip);

        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
