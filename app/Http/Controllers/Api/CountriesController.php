<?php

namespace App\Http\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Validator;
use Pricing_Services_Twilio;

class CountriesController extends Controller
{
    /**
     * Getting current country from session.
     */
    public function current()
    {
        if (!Session::has('country')) {
            return response()->json(['errors' => ['No stored country.']], 404);
        }

        return response()->json(['country' => Session::get('country')]);
    }

    /**
     * Getting list of countries where user can buy a number.
     */
    public function list(Pricing_Services_Twilio $twilio)
    {
        // obviously need to paginate results, but gonna stop with that
        try {
            $countries = $twilio->phoneNumberCountries->getPage(0, 100)->getItems();
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]], 400);
        }

        $result = [];

        foreach ($countries as $country) {
            $result[] = [
                'name' => $country->country,
                'iso' => $country->iso_country
            ];
        }

        // split for 4 column layout
        $chunkSize = ceil(count($result) / 4);
        $splitedCountries = array_chunk($result, $chunkSize);

        return response()->json(['items' => $splitedCountries]);
    }

    /**
     * Store country data to session.
     */
    public function keep(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'country_iso' => 'required',
            'country_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $key = 'country';

        Session::put($key, [
            'name' => $request->get('country_name'),
            'iso' => $request->get('country_iso')
        ]);

        return reponse()->json(['key' => $key], 201);
    }
}
