<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response("api");
});

Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
Route::get('/users/current-weather', [\App\Http\Controllers\UserController::class, 'currentWeatherOverview']);
Route::get('/users/{userId}/current-weather', [\App\Http\Controllers\UserController::class, 'currentWeather'])
    ->whereNumber("userId");
