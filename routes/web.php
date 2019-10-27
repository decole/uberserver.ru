<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'SiteController@index');

Route::any('/sensors', 'SensorsController@showSensors');

Route::any('/telegram/webhook', 'TeleBotController@webhook');

Route::any('/telegram/set-webhook', 'TeleBotController@setWebhook');

Route::any('/watering', 'WaterController@showSwifts');

Route::any('/home_swifts', 'SiteController@showHomeSwifts');

Route::get('/chart', 'SensorsController@chartShow');

Route::get('/alarm', function () {
    return view('index', [
        'page_title' => 'Мониторинг аварийности'
    ]);
});

Route::any('/alice', 'AliceController@actionIndex');

Route::get('/ping', 'SiteApiController@pingSite');

Auth::routes();

Route::get('/home', 'SiteController@index')->name('home');

Route::any('/alice_home', 'AliceController@actionSmartHome');

Route::any('/alice_home/authorize', 'AliceAPIController@actionAutorize');

Route::any('/alice_home/token', 'AliceAPIController@actionTokinizer');