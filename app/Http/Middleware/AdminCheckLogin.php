<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCheckLogin
{
    /**
     * Method handle
     *
     * @param Request $request [explicite description]
     * @param Closure $next    [explicite description]
     * @param string  $guard   [explicite description]
     *
     * @return void
     */
    public function handle(Request $request, Closure $next, string $guard)
    {

        if (Auth::guard($guard)->check()) {
            $userType = Auth::guard($guard)->user()->user_type;
            if (in_array($userType, [User::TYPE_ADMIN, User::TYPE_STAFF])) {
                return redirect()->route('admin.dashboard');
            }
            if (in_array($userType, [User::TYPE_CLIENT])) {
                return redirect()->route('client.dashboard');
            }
        }
        $response = $next($request);
        return  $response->header(
            'Cache-Control',
            'nocache, no-store, max-age=0, must-revalidate'
        )->header('Pragma', 'no-cache')
            ->header('Expired', '0');
    }
}
