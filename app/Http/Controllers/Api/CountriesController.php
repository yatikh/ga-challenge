<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Session;

class CountriesController extends Controller
{
    /**
     * Store country id to session.
     */
    public function keep(Request $request)
    {
        // validate request
        $this->validate($request, [
            'country_iso' => 'required',
            'country_name' => 'required'
        ]);

        Session::put('country', [
            'name' => $request->get('country_name'),
            'iso' => $request->get('country_iso')
        ]);

        return redirect('/');
    }
}
