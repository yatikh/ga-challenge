<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

use App\Http\Requests;
use App;
use Session;
use Services_Twilio;
use Services_Twilio_Twiml;
use Pricing_Services_Twilio;
use App\Models\Phonenumber;
use App\Models\Call;

class TwilioController extends Controller
{
    public function countries(Pricing_Services_Twilio $twilio, MessageBag $messageBag)
    {
        // obviously need to paginate results, but gonna stop with that
        try {
            $countries = $twilio->phoneNumberCountries->getPage(0, 100)->getItems();
        } catch (\Exception $e) {
            $messageBag->add('Twilio REST', $e->getMessage());
            return back()->withErrors($messageBag);
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

        return view('pages.countries', ['countries' => $splitedCountries]);
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
     *
     * @param Request Current request.
     */
    public function buy(Request $request, Services_Twilio $twilio, MessageBag $messageBag)
    {
        // validate request
        $this->validate($request, [
            'phonenumber' => 'required',
        ]);

        // buy fouded number
        try {
            $bouhtNumber = $twilio->account->incoming_phone_numbers->create([
                'PhoneNumber' => $request->get('phonenumber'),
                'VoiceUrl' => 'https://demo.twilio.com/welcome/voice/',
                'SmsUrl' => 'https://demo.twilio.com/welcome/sms/reply/',
            ]);
        } catch (\Exception $e) {
            $messageBag->add('Twilio REST', $e->getMessage());
            return back()->withErrors($messageBag);
        }

        // get country from session
        $country = Session::get('country');

        // store to DB
        $phonenumber = new Phonenumber;
        $phonenumber->number = $bouhtNumber->phone_number;
        // $phonenumber->number = $request->get('phonenumber');
        $phonenumber->country_iso = $country['iso'];
        $phonenumber->save();

        return back();
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
        $call->from_number = $request->get('From');
        $call->from_name = $request->get('CallerName');
        $call->from_country = $request->get('FromCountry');
        $call->save();

        // answer to call
        $twiml->say('Hello, this is an answer for the call.', ['voice' => 'alice']);

        return response($twiml, 200)->header('Content-Type', 'application/xml');
    }
}
