<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Utils;
use App\Models\UserOption;
use App\Models\UserTransactionCycle;
use Carbon\Carbon;
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
        $now = now($options->timezone)->timezone('UTC')->toIso8601String();

        $currentTransactionCycle = UserTransactionCycle::where('user_id', $request->user()->id)
            ->whereRaw("'$now' BETWEEN active_from AND active_until")
            ->first();

        if(!filled($currentTransactionCycle)){
            $transactionCycles = UserTransactionCycle::where('user_id', $request->user()->id)->orderByDesc('active_from')->get();
            $start = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff);

            if(!filled($transactionCycles)){
                // usually when run for the first time: just create a new transaction cycle
                $end = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->addMonth()->toIso8601String();
                $this->createTransactionCycle($request->user()->id, $options->allocated_budget, $start->copy()->toIso8601String(), $end);
            } else {
                // when there are existing transaction cycles, then get the statement dates from the previous end to now
                // then create new transaction cycles for them
                $prevEnd = Carbon::parse($transactionCycles[0]->active_until, 'UTC');
                $dates = $this->createStatementDateArray($prevEnd, $start);

                for($i = 0; $i < count($dates) - 1; $i++){
                    $this->createTransactionCycle($request->user()->id, $options->allocated_budget, $dates[$i], $dates[$i + 1]);
                }
            }
        }

        return $next($request);
    }

    /**
     * Saves a new transaction cycle data for the user
     *
     * @param int $userID
     * @param float $budget
     * @param string $startDateTime
     * @param string $endDateTime
     * @return bool
     */
    public function createTransactionCycle(int $userID, float $budget, string $startDateTime, string $endDateTime)
    {
        $transactionCycle = new UserTransactionCycle();
        $transactionCycle->user_id = $userID;
        $transactionCycle->allocated_budget = $budget;
        $transactionCycle->active_from = $startDateTime;
        $transactionCycle->active_until = $endDateTime;
        $transactionCycle->save();

        return true;
    }

    /**
     * Creates an array of statement dates between two given dates
     *
     * @param Carbon $from
     * @param Carbon $to
     * @return array
     */
    public function createStatementDateArray(Carbon $from, Carbon $to)
    {
        $fromTemp = $from->copy();
        $dates[] = $fromTemp->toIso8601String();

        while($fromTemp->ne($to)) {
            $fromTemp->addMonth();
            $dates[] = $fromTemp->toIso8601String();
        }

        return $dates;
    }
}
