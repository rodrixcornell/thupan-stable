<?php

namespace Service\Http;

use \Service\Http\Request;
use \Service\Http\Response;

class Redirect {

    public static function to($url) {
        Response::redirect($url);
    }

}
