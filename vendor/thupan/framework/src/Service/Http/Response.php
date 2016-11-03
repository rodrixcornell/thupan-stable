<?php

namespace Service\Http;

use \Kernel\View;

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
        View::assign($key, $value);
    }
}
