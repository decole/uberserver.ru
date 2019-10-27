<?php

namespace App\Http\Controllers;

use App\Alice;
use App\AliceSecure;
use App\Weather;
use Illuminate\Http\Request;
use App\User;

class AliceAPIController extends Controller
{
    public function __construct()
    {
        //
    }

    public function get(Request $request)
    {
        $user_id = $request->get("uid", 0);
        $user = User::find($user_id);
        return $user;
    }

    public function actionAutorize()
    {
        return view('auth.login');
    }

    public function actionTokinizer()
    {
        return view('auth.login');
    }

}
