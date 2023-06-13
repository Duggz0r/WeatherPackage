<?php

namespace Duggz0r\WeatherPackage\Services;

use Duggz0r\WeatherPackage\Models\Forecast;
use Duggz0r\WeatherPackage\Models\IPAddress;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class WeatherService implements WeatherServiceInterface
{
    private ?IPAddress $ipAddress;

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

        $this->storeForecast($forecast);
        // Cache the forecast for 1 hour
        Cache::put($cacheKey, $forecast, 60);

        return $forecast;
    }

    public function getLocationByIp($ip): array
    {
        $this->ipAddress = IPAddress::byIPAddress($ip)->first();

        if ($this->ipAddress) {
            return $this->ipAddress->toArray();
        }

        $response = (new Client())->get('http://ip-api.com/json/' . $ip);

        $data = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->storeIPAddress($data, $ip);

        return $data;
    }

    public function storeIPAddress(array $data, string $ip): void
    {
        $this->ipAddress = IPAddress::create(array_merge($data, ['ip_address' => $ip]));
    }

    public function storeForecast(array $forecastData): void
    {
        $mappedData = collect($forecastData)->map(function ($data): array {
            return [
                'date' => $data['date'],
                'location' => $data['location'],
                'temperature' => $data['temperature'],
                'description' => $data['description'],
                'icon' => $data['icon'],
                'ip_address_id' => $this->ipAddress->id,
            ];
        })->toArray();

        Forecast::insert($mappedData);
    }
}
