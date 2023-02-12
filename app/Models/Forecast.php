<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use App\Traits\CityTrait;


class Forecast extends Model
{
    private const OPENWEATHER_MAP_URL = 'https://api.openweathermap.org/data/2.5/';

    private const OPENWEATHER_MAP_API_KEY = '6c3975c3cd09c870239a94ed2b503c82';

    private const RECORD_LIMIT = 8;

    private const SORT_POPULARITY = 'POPULARITY';

    private const UNIT_TYPE = 'metric';

    use HasFactory;
    use CityTrait;

    /**
     * Retrieve nearby places of the city
     *
     * @param string $city
     * @return array
     */
    public function getForecast(string $city)
    {
        $response = Http::withHeaders([
        ])->get(self::OPENWEATHER_MAP_URL.'forecast', [
            'appid' => self::OPENWEATHER_MAP_API_KEY,
            'q' => self::getCityCode($city),
            'cnt' => self::RECORD_LIMIT,
            'units' => self::UNIT_TYPE
        ]);
        if ($response->ok()) {
            $cityForcast = [];
            $forecasts = $response['list'];
            foreach($forecasts as $forecast) {
                $cityForcast[$forecast['dt']] = [
                    'date' => $forecast['dt_txt'],
                    'time' => date('g A', strtotime($forecast['dt_txt'])),
                    'time_24' => date('G', strtotime($forecast['dt_txt'])),
                    'type' => $forecast['weather'][0]['main'],
                    'description' => $forecast['weather'][0]['description'],
                    'icon' => 'https://openweathermap.org/img/wn/'.$forecast['weather'][0]['icon'].'@2x.png',
                    'temp_min' => $forecast['main']['temp_min'],
                    'temp_max' => $forecast['main']['temp_max'],
                    'temp' => $forecast['main']['temp'],
                    'wind' => $forecast['wind']
                ];
            }
            return $cityForcast;
        } else {
            return['error' => '3P API Fetch Error'];
        }
    }
}
