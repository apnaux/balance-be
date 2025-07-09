<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfRegistrationIsAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userCount = User::count();
        if($userCount >= 1 && !config('app.registration_allowed')){
            return response()->json([
                'message' => 'Registration is not allowed'
            ], 401);
        }

        return $next($request);
    }
}
