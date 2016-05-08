<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'DefaultController@index');

Route::post('countries', 'CountriesController@keep');

Route::get('twilio/countries', 'TwilioController@countries');
Route::get('twilio/phonenumbers/{countryCode}', 'TwilioController@phonenumbers');
Route::post('twilio/buy', 'TwilioController@buy');
Route::post('twilio/voice/incoming', 'TwilioController@voiceIncoming');
// Route::post('twilio/sms/incoming', 'TwilioController@smsIncoming');
