<?php

namespace App\Http\Controllers;

use App\Contracts\WeatherService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->select(["id", "name"])
            ->orderBy('id')
            ->cursorPaginate(15);

        return $users;
    }

    public function currentWeather(int $userId, WeatherService $weatherService)
    {
        $user = User::findOrFail($userId);

        $currentWeather = Cache::remember("cw:$userId", 3600, function () use ($weatherService, $user) {
            return $weatherService->getCurrentWeather($user->latitude, $user->longitude)->toArray();
        });

        return response()->json($currentWeather);
    }

    public function currentWeatherOverview(Request $request, WeatherService $weatherService)
    {
        $userIds = explode(",", $request->query("users") ?? '');

        Validator::make(["users" => $userIds], [
            "users" => "array|max:15",
            "users.*" => "integer|distinct",
        ])->validate();

        if (empty($userIds)) {
            return response()->json([]);
        }

        sort($userIds);

        $overview = Cache::remember("bulk-cw:".base64_encode(implode("_",$userIds)), 3600,function () use ($weatherService, $userIds) {
            $users = User::select(['id', 'latitude', 'longitude'])->whereIn('id', $userIds)->orderBy("id")->get();
            $locations = $users->map(fn(User $user) => ["lat" => $user->latitude, "lon" => $user->longitude]);

            $weatherData = $weatherService->bulkGetCurrentWeather($locations->toArray());

            $result = [];

            foreach ($users as $key => $user) {
                if (!$weatherData[$key]) {
                    continue;
                }

                $result[] = [
                    "userId" => $user->id,
                    "icon" => $weatherData[$key]->icon,
                    "status" => $weatherData[$key]->status,
                    "temp" => $weatherData[$key]->temp,
                ];
            }

            return $result;
        });

        return response()->json($overview);
    }
}
