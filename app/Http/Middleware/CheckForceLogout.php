<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckForceLogout
{
    /**
     * Method handle
     *
     * @param Request $request [explicite description]
     * @param Closure $next    [explicite description]
     *
     * @return void
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            /**
             * User
             *
             * @var User $user
            */
            $user = auth()->user();

            if ($user->should_re_login || ($user->status == User::STATUS_INACTIVE)) {
                return forcedLogout($request, $user);
            }
        }
        return $next($request);
    }
}
