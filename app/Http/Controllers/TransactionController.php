<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\MakeTransactionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
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
        $options = Auth::user()->option;
        $previous = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->subMonth()->toDateTimeString();
        $now = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->toDateTimeString();
        $next = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->addMonth()->toDateTimeString();

        $details = Transaction::select([
            DB::raw("SUM(
                CASE WHEN created_at >= '$now' AND created_at <= '$next'
                THEN amount ELSE 0 END
            ) as statement_balance"),
            DB::raw("SUM(
                CASE WHEN created_at >= '$previous' AND created_at <= '$now'
                THEN amount ELSE 0 END
            ) as previous_balance"),
        ])
            ->where('user_id', Auth::id())
            ->whereNotNull('posted_at')
            ->where(function ($query) use ($previous, $next) {
                $query->whereBetween('created_at', [$previous, $next])
                    ->orWhereBetween('posted_at', [$previous, $next]);
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
            'iterations' => 'integer|min:0|required'
        ]);

        $options = Auth::user()->option;
        $current = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->subMonths($request->iterations)->toDateTimeString();
        $next = Utils::getProperStatementDate($options->timezone, $options->cycle_cutoff)->subMonths($request->iterations - 1)->toDateTimeString();

        $details = Transaction::select([
            DB::raw("SUM(
                CASE WHEN created_at >= '$current' AND created_at <= '$next'
                THEN amount ELSE 0 END
            ) as statement_balance"),
        ])
            ->where('user_id', Auth::id())
            ->whereNotNull('posted_at')
            ->where(function ($query) use ($current, $next) {
                $query->whereBetween('created_at', [$current, $next])
                    ->orWhereBetween('posted_at', [$current, $next]);
            })
            ->first();

        $transactions = Transaction::with([
                'tag',
                'transactable'
            ])
            ->where('user_id', Auth::id())
            ->whereNotNull('posted_at')
            ->where(function ($query) use ($current, $next) {
                $query->whereBetween('created_at', [$current, $next])
                    ->orWhereBetween('posted_at', [$current, $next]);
            })
            ->paginate(20);

        return response()->json([
            'current' => $current,
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
