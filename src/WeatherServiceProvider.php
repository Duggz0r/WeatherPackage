<?php

namespace Duggz0r\WeatherPackage;

use Duggz0r\WeatherPackage\Drivers\OpenWeatherMapDriver;
use Duggz0r\WeatherPackage\Services\WeatherService;
use Duggz0r\WeatherPackage\Services\WeatherServiceInterface;
use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/weather.php' => config_path('weather.php'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'weather-package');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    public function register(): void
    {
        $this->app->bind(WeatherServiceInterface::class, function ($app): WeatherService {
            return new WeatherService();
        });
    }
}
