<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Phonenumber;

class DefaultController extends Controller
{
    public function index()
    {
        if (!Session::has('country')) {
            return redirect('twilio/countries');
        }

        $country = Session::get('country');

        $phonenumbers = Phonenumber::where([
            'country_iso' => $country['iso']
        ])->get();

        return view('pages.index', [
            'country' => $country,
            'phonenumbers' => $phonenumbers
        ]);
    }
}
