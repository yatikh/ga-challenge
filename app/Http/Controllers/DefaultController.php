<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Country;

class DefaultController extends Controller
{
    public function index()
    {
        if (!Session::has('country')) {
            return redirect('countries');
        }

        $country = Country::find(Session::get('country'));

        return view('pages.index', ['country' => $country]);
    }
}
