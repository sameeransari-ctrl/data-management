<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class ActiveUserAuthentication
{    
    /**
     * Method handle
     *
     * @param Request $request [explicite description]
     * @param Closure $next [explicite description]
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        //$authGuard = Auth::guard('sanctum');
        $user = auth()->user();
        //$user = $authGuard->user();

        if (!$user || $user->status != User::STATUS_ACTIVE) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
