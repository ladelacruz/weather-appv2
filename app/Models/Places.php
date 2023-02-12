<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use App\Traits\CityTrait;


class Places extends Model
{
    private const FOURSQUARE_PLACES_URL = 'https://api.foursquare.com/v3/places/';

    private const FOUR_SQUARE_AUTH_KEY = 'fsq30VeIv723FHi3DzhXQ2tHA/YoR7yg4tgNSB9BvOInVak=';

    private const RECORD_LIMIT = 16;

    private const SORT_POPULARITY = 'POPULARITY';

    private const SORT_NEWEST = 'NEWEST';

    private const IMAGE_SMALL_SIZE = '270x400';

    private const IMAGE_ORIGINAL_SIZE = 'original';

    use HasFactory;
    use CityTrait;

    /**
     * Retrieve nearby places of the city
     *
     * @param string $city
     * @return array
     */
    public function getPlaces(string $city) : array
    {
        $response = Http::withHeaders([
            'Authorization' => self::FOUR_SQUARE_AUTH_KEY,
        ])->get(self::FOURSQUARE_PLACES_URL.'search', [
            'near' => self::getCityCode($city),
            'limit' => self::RECORD_LIMIT,
            'sort' => self::SORT_POPULARITY

        ]);
        if ($response->ok()) {
            $cityPlaces = [];
            $places = $response['results'];
            // return $places;
            foreach($places as $place) {
                $cityPlaces[$place['fsq_id']] = [
                    'name' => $place['name'],
                    'categories' => [
                        'name' => $place['categories'][0]['name'],
                        'other_count' => count($place['categories'])-1
                    ],
                    'formatted_address' => $place['location']['formatted_address'],
                    'image' => self::getPlaceImage($place['fsq_id']),
                ];
            }
            return $cityPlaces;
        } else {
            return['error' => '3P API Fetch Error'];
        }
    }

    /**
     * Retrieve the latest review of the place
     *
     * @param string $fsqId
     * @return array
     */
    public function getReviews(string $fsqId) : array
    {
        $response = Http::withHeaders([
            'Authorization' => self::FOUR_SQUARE_AUTH_KEY,
        ])->get(self::FOURSQUARE_PLACES_URL.$fsqId.'/tips', [
            'limit' => 1,
            'sort' => self::SORT_NEWEST
        ]);
        if ($response->ok()) {
            return $response[0];
        }
        return $response->json();
    }

    /**
     * Get the image of the place
     *
     * @param string $fsqId
     * @return string
     */
    public function getPlaceImage(string $fsqId) : string
    {
        $response = Http::withHeaders([
            'Authorization' => self::FOUR_SQUARE_AUTH_KEY,
        ])->get(self::FOURSQUARE_PLACES_URL.$fsqId.'/photos',[
            'limit' => 1,
            'sort' => self::SORT_NEWEST
        ]);
        if ($response->ok()) {
            if(isset($response[0]) && !empty($response[0])) {
                $image = $response[0];
                return $image['prefix'] . self::IMAGE_SMALL_SIZE . $image['suffix'];
            } else {
                return 'http://127.0.0.1:8000/storage/images/japan-270x400.jpg';
            }
        }
        return '';
    }
}
