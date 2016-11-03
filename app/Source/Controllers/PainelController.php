<?php

namespace App\Source\Controllers;

use \App\Source\Controllers\Interfaces\Crud as CRUD;
use \App\Core\AppController as Controller;
use \App\Core\AppModel as Model;
use \App\Core\AppView  as View;
use \Service\Http\Request;

class PainelController extends Controller implements CRUD {

    public $auth = true;

    public function index() {
        Request::post($data, ['twig' => true]);
        View::render('painel/index');
    }

    public function edit($id) {
        echo 'voce editou oitem ' . $id;
    }

    public function create() {
        echo 'voce criou o item';
    }

    public function delete($id) {
        echo 'voce deletou o item '. $id;
    }

    public function save() {
        echo 'voce salvou o item';
    }
}
