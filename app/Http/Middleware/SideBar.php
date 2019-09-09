<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SideBar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        static $sideBarComponent;

        $id = Auth::id();
        $request->sideBarComponent = [
            'user_id' => $id,
        ];

//        var_dump( $id );
        return $next($request);
    }
}
