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

        $country = Country::with('phonenumbers')->find(Session::get('country'));
        $phonenumbers = $country->phonenumbers()->get();

        return view('pages.index', [
            'country' => $country,
            'phonenumbers' => $phonenumbers
        ]);
    }
}
