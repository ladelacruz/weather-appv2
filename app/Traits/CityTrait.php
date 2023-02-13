<?php

namespace App\Traits;


trait CityTrait {

    /**
     * Get the city's foursquare QS ready format
     *
     * @param string $city
     * @return string
     */
    public function getCityCode(string $city) : string
    {
        switch ($city) {
            case 'Tokyo':
                return 'Tokyo,JP';
                break;
            case 'Yokohama':
                return 'Yokohama,JP';
                break;
            case 'Kyoto':
                return 'Kyoto,JP';
                break;
            case 'Osaka':
                return 'Osaka,JP';
                break;
            case 'Sapporo':
                return 'Sapporo,JP';
                break;
            case 'Nagoya':
                return 'Nagoya,JP';
                break;
            default:
                return 'Tokyo,JP';
                break;
        }
    }
}