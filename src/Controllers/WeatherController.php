<?php

namespace Duggz0r\WeatherPackage\Controllers;

use Duggz0r\WeatherPackage\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeatherController
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function showWeather(): View
    {
        return view('weather-package::weather', [
            'forecast' => [],
            'ipAddress' => $this->getIPAddress(),
        ]);
    }

    public function updateWeather(Request $request): View
    {
        return view('weather-package::weather', [
            'forecast' => $this->weatherService->getWeatherForecastByIp($ip),
            'ipAddress' => $request->input('ipAddress'),
        ]);
    }
    
    private function getIPAddress(): string
    {
        return $_SERVER['REMOTE_ADDR' === '127.0.0.1' ? '123.211.61.50' : $_SERVER['REMOTE_ADDR';
    }
}
