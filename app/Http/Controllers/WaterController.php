<?php

namespace App\Http\Controllers;

use App\Relays;
use Illuminate\Http\Request;

class WaterController extends Controller
{
    //Route::get('/watering', function () {
    //    return view('watering', [
    //        'page_title' => 'Автополив'
    //    ]);
    //});
    public function showSwifts()
    {
        $sensors = Relays::all();

        foreach ($sensors as $key=>$value) {
            if ($value['state'] == 0) {
                $sensors[$key]['state'] = 'off';
            }
            if ($value['state'] == 1) {
                $sensors[$key]['state'] = 'on';
            }
        }

        return view('watering', [
                'page_title' => 'Данные сенсоров',
                'ralays' =>$sensors,
            ]
        );
    }
}
