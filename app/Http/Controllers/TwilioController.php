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
     * List of available phone numbers for purchasing.
     * @param string Iso code of a country.
     * @return JSON
     */
    public function phonenumbers($countryCode)
    {
        // search a number
        try {
            $numbers = $this->twilio->account->available_phone_numbers->getList(
                $countryCode,
                'Local',
                ['VoiceEnabled' => 'true']
            );
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        $items = [];

        foreach ($numbers->available_phone_numbers as $number) {
            $items[] = $number->phone_number;
        }

        return ['items' => $items];
    }

    /**
     * Buy a phonenumber in particular country.
     * @param Request Current request.
     */
    public function buy(Request $request)
    {
        // validate request
        $this->validate($request, [
            'phonenumber' => 'required',
            'country_id' => 'required'
        ]);

        // get country object
        $country = Country::find($request->get('country_id'));

        // buy fouded number
        // $bouhtNumber = $this->twilio->account->incoming_phone_numbers->create([
        //     "VoiceUrl" => "http://demo.twilio.com/docs/voice.xml",
        //     "PhoneNumber" => $request->get('phonenumber')
        // ]);

        // store to DB
        $phonenumber = new Phonenumber;
        // $phonenumber->number = $bouhtNumber->phone_number;
        $phonenumber->number = $request->get('phonenumber');
        $phonenumber->setCountry($country);
        $phonenumber->save();

        return back();
    }

    public function voiceIncoming()
    {
        # code...
    }
}
