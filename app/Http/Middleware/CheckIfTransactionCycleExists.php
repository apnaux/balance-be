<?php

namespace App\Http\Middleware;

use App\Helpers\Utils;
use App\Models\UserOption;
use App\Models\UserTransactionCycle;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfTransactionCycleExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $options = UserOption::where('user_id', $request->user()->id)->first();
        $now = now($options->timezone)->toDateTimeString();

        $transactionCycle = UserTransactionCycle::where('user_id', $request->user()->id)
            ->whereRaw("'$now' BETWEEN active_from AND active_until")
            ->first();

        if(!filled($transactionCycle))
        {
            $start = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->toDateTimeString();
            $end = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->addMonth()->toDateTimeString();

            $transactionCycle = new UserTransactionCycle();
            $transactionCycle->user_id = $request->user()->id;
            $transactionCycle->allocated_budget = $options->allocated_budget;
            $transactionCycle->active_from = $start;
            $transactionCycle->active_until = $end;
            $transactionCycle->save();
        }

        return $next($request);
    }
}
