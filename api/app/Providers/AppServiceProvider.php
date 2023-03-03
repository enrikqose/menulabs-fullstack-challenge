<?php

namespace App\Providers;

use App\Contracts\WeatherService;
use App\Services\WeatherService\OpenWeatherMapService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(WeatherService::class, function ($app) {
            return new OpenWeatherMapService(env("OPEN_WEATHER_MAP_API_KEY"));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
