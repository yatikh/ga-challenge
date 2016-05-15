<?php

namespace App\Http\Controllers;

use Session;

class DefaultController extends Controller
{
    public function index()
    {
        return view('layouts.app');
    }
}
