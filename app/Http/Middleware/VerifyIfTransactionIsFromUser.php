<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyIfTransactionIsFromUser
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

            $transaction = Transaction::where('id', $request->id)
                ->where('user_id', Auth::id());

            if($transaction->doesntExist()){
                return response()->json([
                    'message' => 'This transaction does not exist.'
                ], 422);
            }
        }

        return $next($request);
    }
}
