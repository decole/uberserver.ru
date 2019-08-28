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

Route::get('/login', function () {
    return view('login', [
        'page_title' => 'Авторизация'
    ]);
});

Route::get('/template', function () {
    return view('index', [
        'page_title' => 'Шаблон'
    ]);
});

Route::any('/sensors', 'SensorsController@showSensors');

Route::any('/api/sensor-state', 'SensorsController@stateSensorsGet');

Route::any('/api/relay-state', 'SensorsController@stateRelaysGet');

Route::any('/api/relay-set', 'SensorsController@stateRelaysSet');

Route::any('/api/leakage', 'SensorsController@stateLeakageGet');

Route::any('/api/emergency-stop', 'SensorsController@emergencySensor');

Route::any('/telegram/webhook', 'TeleBotController@webhook'); // not worked

Route::any('/telegram/set-webhook', 'TeleBotController@setWebhook'); // not worked

Route::get('/template', function () {
    return view('index', [
        'page_title' => 'Шаблон'
    ]);
});

Route::any('/watering', 'WaterController@showSwifts');

Route::get('/chart', function () {
    return view('index', [
        'page_title' => 'Графики - Аналитика'
    ]);
});

Route::get('/alarm', function () {
    return view('index', [
        'page_title' => 'Мониторинг аварийности'
    ]);
});
