<?php

namespace App\Http\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Validator;
use Services_Twilio;
use App\Models\Phonenumber;

class PhonenumbersController extends Controller
{
    /**
     * Getting phonenumber for country.
     */
    public function current($countryCode)
    {
        $phonenumber = Phonenumber::where([
            'country_iso' => $countryCode
        ])->first();

        return response()->json(['phonenumber' => $phonenumber->number]);
    }

    /**
     * List of available phone numbers for purchasing.
     *
     * @param string Iso code of a country.
     * @return JSON
     */
    public function list(Services_Twilio $twilio, $countryCode)
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
    public function purchasing(Request $request, Services_Twilio $twilio)
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
}
