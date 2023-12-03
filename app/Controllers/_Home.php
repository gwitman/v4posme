<?php

namespace App\Controllers;

class _Home extends _BaseController
{
    public function index()
    {
        return view('welcome_message');//--finview-r
    }
}
