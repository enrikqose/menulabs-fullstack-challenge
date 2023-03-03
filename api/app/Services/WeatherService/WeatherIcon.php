<?php

namespace App\Services\WeatherService;

enum WeatherIcon: string
{
    case ClearDay = 'clear-day';
    case ClearNight = 'clear-night';
    case Cloudy = 'cloudy';
    case Foggy = 'foggy';
    case PartlyCloudyDay = 'partly-cloudy-day';
    case PartlyCloudyNight = 'partly-cloudy-night';
    case Rainy = 'rainy';
    case Snowing = 'snowing';
    case Thunderstorm = 'thunderstorm';

}
