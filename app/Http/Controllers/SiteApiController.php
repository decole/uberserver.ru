<?php

namespace App\Http\Controllers;

use App\Alice;
use App\AliceSecure;
use App\Helpers\MqttHelper;
use App\Helpers\TelegramHelper;
use App\Weather;
use Illuminate\Http\Request;

class SiteApiController extends Controller
{
    // @Todo убрать в WebApiController
    public function pingSite()
    {
        $status = [
            'status' => 'ok',
        ];

        return response()->json($status);

    }

}
