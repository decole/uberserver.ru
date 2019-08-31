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

Route::any('/api/sensor-state', 'SensorsController@stateSensorsGet');

Route::any('/api/relay-state', 'SensorsController@stateRelaysGet');

Route::any('/api/relay-set', 'SensorsController@stateRelaysSet');

Route::any('/api/leakage', 'SensorsController@stateLeakageGet');

Route::any('/api/emergency-stop', 'SensorsController@emergencySensor');

Route::any('/telegram/webhook', 'TeleBotController@webhook'); // not worked

Route::any('/telegram/set-webhook', 'TeleBotController@setWebhook'); // not worked

Route::any('/watering', 'WaterController@showSwifts');

Route::get('/chart', 'SensorsController@chartShow');

Route::get('/api/chart', 'SensorsController@chartShowGet');



Route::get('/alarm', function () {
    return view('index', [
        'page_title' => 'Мониторинг аварийности'
    ]);
});

Auth::routes();

Route::get('/home', 'SiteController@index')->name('home');
