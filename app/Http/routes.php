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

Route::get('api/countries/current', 'Api/CountriesController@current');
Route::get('api/countries', 'Api/CountriesController@list');
Route::post('api/countries', 'Api/CountriesController@keep');

Route::get('api/phonenumbers/{countryCode}', 'Api/PhonenumbersController@list');
Route::get('api/phonenumbers/{countryCode}/current', 'Api/PhonenumbersController@current');
Route::post('api/phonenumbers/purchasing', 'Api/PhonenumbersController@purchasing');

Route::post('api/calls/voice/incoming', 'Api/CallsController@voiceIncoming');
