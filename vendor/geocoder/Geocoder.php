<?php

namespace app\Geocoder;

class Geocoder {
    public $apiKey;

    function getLatLong($address) {
        return file_get_contents("https://api.geocod.io/v1.4/geocode?q=".str_replace(" ", "+", $address)."&api_key=".$this->apiKey);
    }
}

?>