<?php

namespace Duggz0r\WeatherPackage\Console\Commands;

use Illuminate\Console\Command;
use Duggz0r\WeatherPackage\Services\WeatherService;

class GetWeatherForecast extends Command
{
    protected $signature = 'weather:forecast {ip}';

    protected $description = 'Retrieve the weather forecast for the given IP address';

    public function handle()
    {
        $ipAddress = $this->argument('ip');
        $weatherService = app(WeatherService::class);
        $forecast = $weatherService->getWeatherForecastByIp($ipAddress);

        $headers = ['Date', 'Location', 'Temperature (°C)', 'Description', 'Icon'];
        $rows = [];

        foreach ($forecast as $data) {
            $rows[] = [
                $data['date'],
                $data['location'],
                $data['temperature'],
                $data['description'],
                $data['icon'],
            ];
        }

        $this->table($headers, $rows);
    }
}
