<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\MakeTransactionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\UserOption;
use App\Models\UserTransactionCycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;
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

        $previousCycleStartData = Carbon::parse($currentCycleData->active_from, 'UTC')->subMonth()->toIso8601String();

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

    public function transactionsPerCycle(Request $request)
    {
        $request->validate([
            'iterations' => 'integer|min:0'
        ]);

        $options = UserOption::where('user_id', Auth::id())->first();
        $cycle = DB::select("
                SELECT
                    UTrC.*,
                    COALESCE(SUM(T.amount), 0) AS 'statement_balance',
                    (SELECT COUNT(*) FROM user_transaction_cycles WHERE user_id = ?) AS 'cycle_counts',
                    UTrC.active_from,
                    UTrC.active_until
                FROM user_transaction_cycles AS UTrC
                LEFT JOIN transactions T ON T.user_id = UtrC.user_id
                    AND T.created_at BETWEEN UTrC.active_from AND UTrC.active_until
                    AND T.transactable_type = ?
                WHERE UTRC.user_id = ?
                GROUP BY UTrC.id
                LIMIT 1 OFFSET ?
            ", [Auth::id(), Account::class, Auth::id(), $request->iterations ?? 0])[0];

        return response()->json([
            'statement_date' => Carbon::parse($cycle->active_from, $options->timezone)->format('Y-m-d'),
            'has_overspent' => $cycle->allocated_budget < $cycle->statement_balance,
            'allocated_budget' => Number::currency(round($cycle->allocated_budget / 100, 2) ?? 0, $options->currency),
            'statement_balance' => Number::currency(round($cycle->statement_balance / 100, 2) ?? 0, $options->currency),
            'active_from' => $cycle->active_from,
            'active_until' => $cycle->active_until,
            'last_item' => $request->iterations >= $cycle->cycle_counts - 1,
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

        $transaction->posted_at = Carbon::now('UTC')->toIso8601String();
        $transaction->save();

        return response()->json([
            'message' => 'The transaction has been posted!'
        ]);
    }
}
