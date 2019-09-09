<?php

namespace App\Http\Controllers;

use App\Relays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Page shown watering swifts
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSwifts(Request $request)
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
                'sidebar'    => $request->sideBarComponent,
            ]
        );

    }

}
