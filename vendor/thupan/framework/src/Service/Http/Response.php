<?php

namespace Service\Http;

class Response {

    public function __construct() {

    }

    public function header() {

    }

    public static function error($number) {

        switch($number) {
            case 404:
                echo 'not found';
            break;
        }

    }

    public static function redirect($url) {
        header("Location: $url");
    }

    public static function assign($key, $value) {
        
    }
}
