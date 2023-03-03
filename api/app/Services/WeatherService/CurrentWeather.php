<?php

namespace App\Services\WeatherService;

class CurrentWeather
{
    public string $status;
    public string $description;
    public WeatherIcon $icon;
    public float $temp;
    public float $feelsLike;
    public float $tempMin;
    public float $tempMax;
    public float $pressure;
    public float $humidity;
    public float $windSpeed;
    public int $windDir;
    public int $visibility;
    public int $clouds;

    public function toArray(): array
    {
        return [
            "status" => $this->status,
            "description" => $this->description,
            "icon" => $this->icon->value,
            "temp" => $this->temp,
            "feelsLike" => $this->feelsLike,
            "tempMin" => $this->tempMin,
            "tempMax" => $this->tempMax,
            "pressure" => $this->pressure,
            "humidity" => $this->humidity,
            "windSpeed" => $this->windSpeed,
            "windDir" => $this->windDir,
            "visibility" => $this->visibility,
            "clouds" => $this->clouds,
        ];
    }
}
