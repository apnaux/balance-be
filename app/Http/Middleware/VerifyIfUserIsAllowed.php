<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyIfUserIsAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(str_contains($request->url(), 'transactions')){
            $request->validate([
                'id' => 'integer|exists:transactions,id|required'
            ]);

            $transaction = Transaction::find($request->user);
            $message = '';

            if($transaction->user_id != Auth::id()){
                $message = 'This transaction does not exist.';
            }

            if(filled($message)){
                return response()->json([
                    'message' => $message
                ], 422);
            }
        }

        return $next($request);
    }
}
