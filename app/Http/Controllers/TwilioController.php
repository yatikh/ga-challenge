<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App;
use Session;
use Services_Twilio;
use App\Models\Phonenumber;
use App\Models\Country;

class TwilioController extends Controller
{
    /**
     * Twilio SDK client.
     */
    protected $twilio;

    /**
     * Resolve our service container dependency.
     * @param Services_Twilio Twilio client.
     */
    public function __construct(Services_Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Buy a phonenumber in particular country.
     * @param Request Current request.
     */
    public function buy(Request $request)
    {
        // validate request
        $this->validate($request, [
            'country_code' => 'required|max:2',
            'country_id' => 'required'
        ]);

        // get country object
        $country = Country::find($request->get('country_id'));

        // search a number
        $numbers = $this->twilio->account->available_phone_numbers->getList(
            $request->get('country_code'),
            'Local',
            [
                'VoiceEnabled' => 'true',
            ]
        );

        // pick the first
        $number = current($numbers->available_phone_numbers);

        // $stubbedNumber = '+34518880342';

        // buy fouded number
        $bouhtNumber = $this->twilio->account->incoming_phone_numbers->create([
            "VoiceUrl" => "http://demo.twilio.com/docs/voice.xml",
            "PhoneNumber" => $number->phone_number
            // "PhoneNumber" => $stubbedNumber
        ]);

        // store to DB
        $phonenumber = new Phonenumber;
        $phonenumber->number = $bouhtNumber->phone_number;
        $phonenumber->setCountry($country);
        $phonenumber->save();

        return back();
    }
}
