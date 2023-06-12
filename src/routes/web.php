<?php

use Duggz0r\WeatherPackage\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('weather', [WeatherController::class, 'showWeather'])->name('weather.show');
Route::post('weather', [WeatherController::class, 'updateWeather'])->name('weather.update');
