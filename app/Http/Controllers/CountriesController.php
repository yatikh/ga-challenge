<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountriesController extends Controller
{
    public function list()
    {
        $countries = Country::all();

        // split for 4 column layout
        $chunkSize = ceil($countries->count() / 4);
        $splitedCountries = $countries->chunk($chunkSize);

        return view('pages.countries', ['countries' => $splitedCountries]);
    }
}
