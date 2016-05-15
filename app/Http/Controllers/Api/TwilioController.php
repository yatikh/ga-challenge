<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App;
use Session;
use Validator;
use Services_Twilio;
use Services_Twilio_Twiml;
use Pricing_Services_Twilio;
use App\Models\Call;
use App\Models\Phonenumber;

class TwilioController extends Controller
{
    /**
     * Getting current country from session.
     */
    public function currentCountry()
    {
        if (!Session::has('country')) {
            return response()->json(['errors' => ['No stored country.']], 404);
        }

        return response()->json(['country' => Session::get('country')]);
    }

    /**
     * Getting phonenumber for country.
     */
    public function phonenumber($countryIso)
    {
        $phonenumber = Phonenumber::where([
            'country_iso' => $countryIso
        ])->first();

        return response()->json(['phonenumber' => $phonenumber->number]);
    }

    /**
     * Getting list of countries where user can buy a number.
     */
    public function countries(Pricing_Services_Twilio $twilio)
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
    public function keepCountry(Request $request)
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

    /**
     * List of available phone numbers for purchasing.
     *
     * @param string Iso code of a country.
     * @return JSON
     */
    public function phonenumbers(Services_Twilio $twilio, $countryCode)
    {
        // debugging purchasing correct number
        // return ['items' => ['+15005550006']];

        // search a number
        try {
            $numbers = $twilio->account->available_phone_numbers->getList(
                $countryCode,
                'Local',
                ['VoiceEnabled' => 'true']
            );
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]], 400);
        }

        $items = [];

        foreach ($numbers->available_phone_numbers as $number) {
            $items[] = $number->phone_number;
        }

        return response()->json(['items' => $items]);
    }

    /**
     * Buy a phonenumber in particular country.
     *
     * @param Request Current request.
     */
    public function buy(Request $request, Services_Twilio $twilio)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'phonenumber' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // buy fouded number
        try {
            $boughtNumber = $twilio->account->incoming_phone_numbers->create([
                'PhoneNumber' => $request->get('phonenumber'),
                'VoiceUrl' => 'https://demo.twilio.com/welcome/voice/',
                'SmsUrl' => 'https://demo.twilio.com/welcome/sms/reply/',
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]], 400);
        }

        // get country from session
        $country = Session::get('country');

        // store to DB
        $phonenumber = new Phonenumber;
        $phonenumber->number = $boughtNumber->phone_number;
        $phonenumber->countryIso = $country['iso'];
        $phonenumber->save();

        return response()->json(['number' => $boughtNumber->phone_number], 201);
    }

    /**
     * Response on incomming voice call from Twilio.
     */
    public function voiceIncoming(Request $request, Services_Twilio_Twiml $twiml)
    {
        // tracking call
        $phonenumber = Phonenumber::where(['number' => $request->get('To')])->first();

        $call = new Call;
        $call->phonenumber()->associate($phonenumber);
        $call->sid = $request->get('CallSid');
        $call->fromNumber = $request->get('From');
        $call->fromName = $request->get('CallerName');
        $call->fromCountry = $request->get('FromCountry');
        $call->save();

        // answer to call
        $twiml->say('Hello, this is an answer for the call.', ['voice' => 'alice']);

        return response($twiml, 200)->header('Content-Type', 'application/xml');
    }
}
