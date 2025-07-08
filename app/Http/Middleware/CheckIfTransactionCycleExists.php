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

        $currentTransactionCycle = UserTransactionCycle::where('user_id', $request->user()->id)
            ->whereRaw("'$now' BETWEEN active_from AND active_until")
            ->first();

        $transactionCycles = UserTransactionCycle::where('user_id', $request->user()->id)->get();

        // TODO: If there are previous transaction cycles, create an array of all the dates
        // starting from the end of the previous transaction cycle to the current date

        if(!filled($currentTransactionCycle))
        {
            $start = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->toDateTimeString();
            $end = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->addMonth()->toDateTimeString();

            $this->createTransactionCycle($request->user()->id, $options->budget, $start, $end);
        }

        return $next($request);
    }

    public function createTransactionCycle(int $userID, float $budget, string $startDate, string $endDate)
    {
        $transactionCycle = new UserTransactionCycle();
        $transactionCycle->user_id = $userID;
        $transactionCycle->allocated_budget = $budget;
        $transactionCycle->active_from = $startDate;
        $transactionCycle->active_until = $endDate;
        $transactionCycle->save();
    }
}
