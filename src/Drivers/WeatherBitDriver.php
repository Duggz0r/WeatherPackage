<?php

namespace Duggz0r\WeatherPackage\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class WeatherBitDriver implements WeatherDriverInterface
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getWeatherForecast(string $latitude, string $longitude): array
    {
        // Call the OpenWeatherMap API to fetch the weather forecast
        $response = (new Client())->get('http://api.weatherbit.io/v2.0/forecast/daily', [
            'query' => [
                'lat' => $latitude,
                'lon' => $longitude,
                'days' => 5,
                'units' => 'metric',
                'key' => config('weather.weatherbit.api_key'),
            ],
        ]);

        return $this->transformData(json_decode($response->getBody(), true));
    }

    public function transformData(array $rawData): array
    {
        $transformedData = [];

        foreach ($rawData['data'] as $item) {
            $transformedItem = [
                'date' => $item['datetime'],
                'temperature' => $item['temp'],
                'description' => $item['weather']['description'],
                'icon' => 'https://cdn.weatherbit.io/static/img/icons/' . $item['weather']['icon'] . '.png',
            ];

            $transformedData[] = $transformedItem;
        }

        return $transformedData;
    }
}


