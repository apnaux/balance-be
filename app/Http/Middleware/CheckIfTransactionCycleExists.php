<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Utils;
use App\Models\UserOption;
use App\Models\UserTransactionCycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $now = now($options->timezone)->timezone('UTC')->toDateTimeString();

        $currentTransactionCycle = UserTransactionCycle::where('user_id', $request->user()->id)
            ->whereRaw("'$now' BETWEEN active_from AND active_until")
            ->first();

        if (empty($currentTransactionCycle)) {
            Log::debug('No current transaction cycle found');
            $latestTransactionCycle = UserTransactionCycle::where('user_id', $request->user()->id)
                ->orderByDesc('active_from')->first();
            $start = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff);

            if (!filled($latestTransactionCycle)) {
                // usually when run for the first time: just create a new transaction cycle
                $end = Utils::getProperStatementDate(
                    $options->timezone,
                    $options->cycle_cutoff
                )->addMonth()->toDateTimeString();
                $this->createTransactionCycle($request->user()->id, $options, $start->copy()->toDateTimeString(), $end);
            } else {
                // when there are existing transaction cycles, then get the statement dates from the previous end to now
                // then create new transaction cycles for them
                $prevEnd = Carbon::parse($latestTransactionCycle->active_until, 'UTC');
                $dates = $this->createStatementDateArray($prevEnd, $start->addMonth());
                for ($i = 0; $i < count($dates) - 1; $i++) {
                    $this->createTransactionCycle($request->user()->id, $options, $dates[$i], $dates[$i + 1]);
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
    public function createTransactionCycle(int $userID, object $option, string $startDateTime, string $endDateTime)
    {
        UserTransactionCycle::create([
            'user_id' => $userID,
            'currency' => $option->currency,
            'total_income' => $option->total_income,
            'to_save' => $option->to_save,
            'active_from' => $startDateTime,
            'active_until' => $endDateTime
        ]);

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
        $dates[] = $fromTemp->toDateTimeString();

        while($fromTemp->ne($to)) {
            $fromTemp->addMonth();
            $dates[] = $fromTemp->toDateTimeString();
        }

        return $dates;
    }
}
