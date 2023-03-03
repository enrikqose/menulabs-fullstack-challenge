<?php

namespace App\Services\WeatherService;

use App\Contracts\WeatherService;
use App\Exceptions\WeatherServiceException;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class OpenWeatherMapService implements WeatherService
{
    const API_URL = "https://api.openweathermap.org/data/2.5";

    private string $apiKey;
    private string $units = "imperial";

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @throws WeatherServiceException
     */
    public function getCurrentWeather(int|string $lat, int|string $lon): CurrentWeather
    {
        $response = Http::get(self::API_URL . "/weather", [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
            "units" => $this->units
        ]);

        if (!$response->successful()) {
            throw new WeatherServiceException($response->body());
        }

        return $this->currentWeatherFromAPI($response->json());
    }

    public function bulkGetCurrentWeather(array $locations): array
    {
        $responses = Http::pool(fn(Pool $pool) => array_map(function ($location) use ($pool) {
            return $pool->get(self::API_URL . "/weather", [
                'lat' => $location["lat"],
                'lon' => $location["lon"],
                'appid' => $this->apiKey,
                "units" => $this->units
            ]);
        }, $locations));

        $result = [];

        foreach ($responses as $response) {
            if (!$response->successful()) {
                $result[] = null;
            }

            $result[] = $this->currentWeatherFromAPI($response->json());
        }

        return $result;
    }

    private function currentWeatherFromAPI(array $response): CurrentWeather
    {
        $currentWeather = new CurrentWeather();
        $currentWeather->status = match ($response["weather"][0]["main"]) {
            "Clouds" => "Cloudy",
            default => $response["weather"][0]["main"]
        };
        $currentWeather->description = ucfirst($response["weather"][0]["description"]);
        $currentWeather->icon = match ($response["weather"][0]["icon"]) {
            '01d' => WeatherIcon::ClearDay,
            '01n' => WeatherIcon::ClearNight,
            '02d' => WeatherIcon::PartlyCloudyDay,
            '02n' => WeatherIcon::PartlyCloudyNight,
            '03d', '03n', '04d', '04n' => WeatherIcon::Cloudy,
            '09d', '09n', '10d', '10n' => WeatherIcon::Rainy,
            '11d', '11n', => WeatherIcon::Thunderstorm,
            '13d', '13n', => WeatherIcon::Snowing,
            '50d', '50n', => WeatherIcon::Foggy,
        };
        $currentWeather->temp = $response["main"]["temp"];
        $currentWeather->feelsLike = $response["main"]["feels_like"];
        $currentWeather->tempMin = $response["main"]["temp_min"];
        $currentWeather->tempMax = $response["main"]["temp_max"];
        $currentWeather->pressure = $response["main"]["pressure"];
        $currentWeather->humidity = $response["main"]["humidity"];
        $currentWeather->windSpeed = $response["wind"]["speed"];
        $currentWeather->windDir = $response["wind"]["deg"];
        $currentWeather->visibility = $response["visibility"];
        $currentWeather->clouds = $response["clouds"]['all'];

        return $currentWeather;
    }
}
