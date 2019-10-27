<?php

namespace App\Http\Middleware;

use App\User;
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
        $id = Auth::id();
        if($id) {
            $user = User::find($id);

            $request->sideBarComponent = [
                'notification' => $user->unreadNotifications,
                'count_notification' => count($user->unreadNotifications),
            ];
        }


        return $next($request);
    }
}
