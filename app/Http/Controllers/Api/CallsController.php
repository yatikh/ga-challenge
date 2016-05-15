<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Services_Twilio_Twiml;
use App\Models\Call;
use App\Models\Phonenumber;

class CallsController extends Controller
{
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
