<?php

namespace Duggz0r\WeatherPackage\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class OpenWeatherMapDriver implements WeatherDriverInterface
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getWeatherForecast(string $latitude, string $longitude): array
    {
        // Call the OpenWeatherMap API to fetch the weather forecast
        $response = (new Client())->get('http://api.openweathermap.org/data/2.5/forecast', [
            'query' => [
                'lat' => $latitude,
                'lon' => $longitude,
                'units' => 'metric',
                'appid' => config('weather.openweathermap.api_key'),
            ],
        ]);

        return $this->transformData(json_decode($response->getBody(), true));
    }

    public function transformData(array $rawData): array
    {
        $groupedData = $this->groupDataByDate($rawData);
        $averagedData = $this->calculateAverages($groupedData);
        return $this->limitData($averagedData, 5);
    }

    private function groupDataByDate(array $rawData): array
    {
        $groupedData = [];

        foreach ($rawData['list'] as $item) {
            $date = date('Y-m-d', strtotime($item['dt_txt']));
            $temperature = $item['main']['temp'];
            $description = $item['weather'][0]['description'];
            $icon = $item['weather'][0]['icon'];

            if (!isset($groupedData[$date])) {
                $groupedData[$date] = [
                    'date' => $date,
                    'temperatures' => [],
                    'descriptions' => [],
                    'icons' => [],
                ];
            }

            $groupedData[$date]['temperatures'][] = $temperature;
            $groupedData[$date]['descriptions'][] = $description;
            $groupedData[$date]['icons'][] = $icon;
        }

        return $groupedData;
    }

    private function calculateAverages(array $groupedData): array
    {
        $averagedData = [];

        foreach ($groupedData as $date => $data) {
            $averageTemperature = array_sum($data['temperatures']) / count($data['temperatures']);
            $roundedTemperature = round($averageTemperature, 2); // Round to 2 decimals

            $descriptionCounts = array_count_values($data['descriptions']);
            $iconCounts = array_count_values($data['icons']);

            // Find the most frequent description
            $mostFrequentDescription = $this->findMostFrequentDescription($descriptionCounts, $data['descriptions']);
            $mostFrequentIcon = $this->findMostFrequentIcon($iconCounts, $data['icons']);

            $data['temperature'] = $roundedTemperature;
            $data['description'] = $mostFrequentDescription;
            $data['icon'] = $mostFrequentIcon;
            $averagedData[] = $data;
        }

        return $averagedData;
    }

    private function findMostFrequentDescription(array $descriptionCounts, array $descriptions): string
    {
        $mostFrequentDescription = '';
        $maxCount = 0;

        foreach ($descriptionCounts as $description => $count) {
            if ($count > $maxCount) {
                $mostFrequentDescription = $description;
                $maxCount = $count;
            }
        }

        // If there is no clear majority, use the description in the middle
        if ($maxCount < count($descriptions) / 2) {
            $middleIndex = floor(count($descriptions) / 2);
            $mostFrequentDescription = $descriptions[$middleIndex];
        }

        return $mostFrequentDescription;
    }

    private function findMostFrequentIcon(array $iconCounts, array $icons): string
    {
        $mostFrequentIcon = '';
        $maxCount = 0;

        foreach ($iconCounts as $icon => $count) {
            if ($count > $maxCount) {
                $mostFrequentIcon = $icon;
                $maxCount = $count;
            }
        }

        // If there is no clear majority, use the description in the middle
        if ($maxCount < count($icons) / 2) {
            $middleIndex = floor(count($icons) / 2);
            $mostFrequentIcon = $icons[$middleIndex];
        }

        return 'https://openweathermap.org/img/wn/' . $mostFrequentIcon . '.png';
    }

    private function limitData(array $data, int $limit): array
    {
        return array_slice($data, 0, $limit);
    }
}


