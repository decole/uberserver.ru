<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:api')->get('/redirect', function (Request $request) {
//    return $request->user();
//});
// @Todo сделать кастом для диалогов
//Route::get('/redirect', function (Request $request) {
//    dump(request()->all());
//    if(empty($request->scope)){
//        $request->scope = 'home';
//    }
//    $query = http_build_query([
//        'client_id' => $request->client_id,
//        'redirect_uri' => 'https://social.yandex.net/broker/redirect',
//        'response_type' => 'code',
//        'scope' => $request->scope,
//        'code' =>$request->code,
//        'state'=>$request->state,
//
//    ]);
//
//    return redirect('https://social.yandex.net/broker/redirect?'.$query);
//});



//Route::middleware('api')->get('/do/get', function (Request $request) {
//    return 'indeed';
//});

Route::middleware('api')->get('/chart', 'WebApiController@chartShowGet');

Route::middleware('api')->get('/sensor-state', 'WebApiController@stateSensorsGet');

Route::middleware('api')->get('/relay-state', 'WebApiController@stateRelaysGet');

Route::middleware('api')->get('/relay-set', 'WebApiController@stateRelaysSet');

Route::middleware('api')->get('/leakage', 'WebApiController@stateLeakageGet');

Route::middleware('api')->get('/emergency-stop', 'WebApiController@emergencySensor');

Route::middleware('api')->get('/notifySite', 'WebApiController@notify');

Route::middleware('api')->get('/addTimer', 'WebApiController@addTimer');
