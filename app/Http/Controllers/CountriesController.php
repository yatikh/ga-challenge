<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Session;

class CountriesController extends Controller
{
    /**
     * Show list of countries.
     */
    public function list()
    {
        $countries = Country::all();

        // split for 4 column layout
        $chunkSize = ceil($countries->count() / 4);
        $splitedCountries = $countries->chunk($chunkSize);

        return view('pages.countries', ['countries' => $splitedCountries]);
    }

    /**
     * Store country id to session.
     */
    public function keep(Request $request)
    {
        if (!$request->has('country')) {
            App::abort(400, 'Bad request.');
        }

        Session::put('country', $request->get('country'));

        return redirect('/');
    }
}
