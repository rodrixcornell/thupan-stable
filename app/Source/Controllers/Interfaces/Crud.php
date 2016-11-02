<?php

namespace App\Source\Controllers\Interfaces;

Interface Crud {
    //view
    public function index();
    //view - form Edit
    public function edit($id);
    //view - form Create
    public function create();
    //action - delete
    public function delete($id);
    //action - save
    public function save();
}
