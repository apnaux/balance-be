<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\MakeTransactionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\UserOption;
use App\Models\UserTransactionCycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(TransactionRequest $request)
    {
        $transactions = $request->retrieve();
        return response()->json($transactions);
    }

    public function summary(Request $request)
    {
        $currentCycleData = UserTransactionCycle::where('user_id', Auth::id())
            ->orderByDesc('active_from')
            ->first();

        $previousCycleStartData = Carbon::parse($currentCycleData->active_from, 'UTC')->subMonth()->toDateTimeString();

        $details = Transaction::select([
            DB::raw("SUM(
                CASE WHEN created_at >= '$currentCycleData->active_from' AND created_at <= '$currentCycleData->active_until'
                THEN amount ELSE 0 END
            ) as statement_balance"),
            DB::raw("SUM(
                CASE WHEN created_at >= '$previousCycleStartData' AND created_at <= '$currentCycleData->active_from'
                THEN amount ELSE 0 END
            ) as previous_balance"),
        ])
            ->where('user_id', Auth::id())
            ->whereNotNull('posted_at')
            ->where(function ($query) use ($previousCycleStartData, $currentCycleData) {
                $query->whereBetween('created_at', [$previousCycleStartData, $currentCycleData->active_until])
                    ->orWhereBetween('posted_at', [$previousCycleStartData, $currentCycleData->active_until]);
            })
            ->first();

        return response()->json([
            'statement_balance' => $details->statement_balance,
            'previous_balance' => $details->previous_balance,
        ]);
    }

    public function transactionCyclesList(Request $request)
    {
        $options = UserOption::where('user_id', Auth::id())->first();

        $transactionCycles = UserTransactionCycle::select([
                'allocated_budget',
                'active_from',
                'active_until'
            ])
            ->where('user_id', Auth::id())
            ->paginate(20)
            ->through(function (UserTransactionCycle $cycle) use ($options) {
                $startDate = Carbon::parse($cycle->active_from, 'UTC')->timezone($options->timezone)->toDateString();

                $cycle->label = $startDate;
                return $cycle;
            });

        return response()->json($transactionCycles);
    }

    public function transactionsPerCycle(Request $request)
    {
        $request->validate([
            'start_date' => 'string|nullable',
            'end_date' => 'string|nullable',
        ]);

        $details = Transaction::select([
            DB::raw("SUM(
                CASE WHEN created_at >= '$request->start_date' AND created_at <= '$request->end_date'
                THEN amount ELSE 0 END
            ) as statement_balance"),
        ])
            ->where('user_id', Auth::id())
            ->whereNotNull('posted_at')
            ->where(function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->orWhereBetween('posted_at', [$request->start_date, $request->end_date]);
            })
            ->first();

        $transactions = Transaction::with([
                'tag',
                'transactable'
            ])
            ->where('user_id', Auth::id())
            ->whereNotNull('posted_at')
            ->where(function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->orWhereBetween('posted_at', [$request->start_date, $request->end_date]);
            })
            ->paginate(20);

        return response()->json([
            'statement_balance' => $details->statement_balance ?? 0,
            'paginated' => $transactions
        ]);
    }

    public function create(MakeTransactionRequest $request)
    {
        $request->make();
        return response()->json([
            'message' => 'The transaction has been saved!'
        ]);
    }

    public function update(Request $request)
    {
        Transaction::find($request->id)
            ->update($request->fields);

        return response()->json([
            'message' => 'The transaction has been updated!'
        ]);
    }

    public function delete(Request $request)
    {
        Transaction::find($request->id)->delete();

        return response()->json([
            'message' => 'The transaction has been deleted!'
        ]);
    }

    public function postTransaction(Request $request)
    {
        $transaction = Transaction::find($request->id);
        if(filled($transaction->posted_at)){
            throw ValidationException::withMessages([
                'transaction_id' => 'This transaction is already posted.'
            ]);
        }

        $transaction->posted_at = Carbon::now('UTC')->toDateTimeString();
        $transaction->save();

        return response()->json([
            'message' => 'The transaction has been posted!'
        ]);
    }
}
