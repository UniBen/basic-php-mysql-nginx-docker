<?php

namespace App\Controllers;

class HomeController
{
    public function index() {
        return view('home.index', ['hello' => 'test']);
    }
}