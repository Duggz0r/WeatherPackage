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
//        $ip = $_SERVER['REMOTE_ADDR'];
        $ip = '123.211.61.50';

        return view('weather-package::weather', [
            'forecast' => [],
            'ipAddress' => $ip,
        ]);
    }

    public function updateWeather(Request $request): View
    {
        $ip = $request->input('ipAddress');

        return view('weather-package::weather', [
            'forecast' => $this->weatherService->getWeatherForecastByIp($ip),
            'ipAddress' => $ip,
        ]);
    }
}
