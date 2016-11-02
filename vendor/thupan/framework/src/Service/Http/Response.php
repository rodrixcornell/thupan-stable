<?php

namespace Service\Http;

class Response {

    public function __construct() {

    }

    public function header() {

    }

    public function error($number) {

        switch($number) {
            case 404:
                echo 'not found';
            break;
        }

    }
}
