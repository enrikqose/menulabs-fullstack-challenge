<?php

namespace App\Contracts;

use App\Services\WeatherService\CurrentWeather;

interface WeatherService {
    public function getCurrentWeather(int|string $lat, int|string $lon): CurrentWeather;
    public function bulkGetCurrentWeather(array $locations): array;
}
