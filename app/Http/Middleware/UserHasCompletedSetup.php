<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserHasCompletedSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $options = Auth::user()->option;
        if(empty($options) && $request->route()->getName() != 'hello'){
            if ($request->header('X-Inertia')) {
                // abort(401, 'You are not authorized to do this action.');
                return redirect()->route('hello')
                    ->withErrors(['error' => 'You need to set things up first before doing that.']);
            }

            return response()->json([
                'message' => 'You should run the first time configuration first before you can access other features.'
            ], 401);
        } else if (filled($options)) {
            if($request->route()->getName() == 'hello'){
                return redirect()->intended('/budgets');
            }
        }

        return $next($request);
    }
}
