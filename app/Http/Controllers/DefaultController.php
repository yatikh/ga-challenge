<?php

namespace App\Http\Controllers;

use Session;

class DefaultController extends Controller
{
    public function index()
    {
        if (!Session::has('country')) {
            return redirect('countries');
        }

        return view('pages.index');
    }
}
