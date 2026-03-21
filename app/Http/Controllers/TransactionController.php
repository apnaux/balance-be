<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\MakeTransactionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\UserOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function list(TransactionRequest $request)
    {
        $transactions = $request->retrieve();
        return response()->json($transactions);
    }

    public function perCycleData(Request $request)
    {
        $request->validate([
            'iterations' => 'integer|min:0|default:0'
        ]);

        $options = UserOption::where('user_id', Auth::id())->first();
        $cycle = DB::select("
                SELECT
                    (MAX(UTrC.total_income) - MAX(UTrC.to_save)) AS 'allocated_budget',
                    COALESCE(SUM(T.amount), 0) AS 'statement_balance',
                    MAX(UTrC.active_from) as 'active_from',
                    MAX(UTrC.active_until) as 'active_until'
                FROM user_transaction_cycles AS UTrC
                LEFT JOIN transactions T ON T.user_id = UTrC.user_id
                    AND T.created_at BETWEEN UTrC.active_from AND UTrC.active_until
                WHERE UTrC.user_id = ?
                GROUP BY UTrC.id
                ORDER BY UTrC.id DESC
                LIMIT 1 OFFSET ?
            ", [Auth::id(), $request->iterations ?? 0])[0];

        $cycleCounts = DB::select("SELECT COUNT(*) AS 'count' FROM user_transaction_cycles WHERE user_id = ?", [Auth::id()])[0];

        $now = Carbon::now($options->timezone)->timezone('UTC');
        $dailySpend = Transaction::where('user_id', Auth::id())
            ->where('created_at', '>=', $now->startOfDay()->toDateTimeString())
            ->where('created_at', '<=', $now->endOfDay()->toDateTimeString())
            ->sum('amount');

        return response()->json([
            'has_overspent' => $cycle->allocated_budget < $cycle->statement_balance,
            'allocated_budget' => Number::currency(round($cycle->allocated_budget / 100, 2) ?? 0, $options->currency),
            'statement_balance' => Number::currency(round($cycle->statement_balance / 100, 2) ?? 0, $options->currency),
            'remaining_balance' => Number::currency(round(($cycle->allocated_budget - $cycle->statement_balance) / 100, 2) ?? 0, $options->currency),
            'daily_spend' => Number::currency(round($dailySpend / 100, 2) ?? 0, $options->currency),
            'active_from' => Carbon::parse($cycle->active_from, 'UTC')->timezone($options->timezone)->format('F d'),
            'active_until' => Carbon::parse($cycle->active_until, 'UTC')->timezone($options->timezone)->format('F d, Y'),
            'total_cycles' => $cycleCounts->count,
            'last_item' => $request->iterations >= $cycleCounts->count - 1,
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
            ->update($request->only(['amount', 'name', 'tag_id', 'transactable_id']));

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

    public function post(Request $request)
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
