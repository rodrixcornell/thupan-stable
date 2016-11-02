<?php

namespace App\Source\Controllers;

use \App\Core\AppController as Controller;
use \App\Core\AppModel as Model;
use \App\Core\AppView  as View;

class AutenticacaoController extends Controller {

    public $auth = false;
    
    public function index() {
        var_dump($this->auth);
    }

}
