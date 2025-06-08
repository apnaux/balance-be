<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeTransactionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(TransactionRequest $request)
    {
        $transactions = $request->retrieve();
        return response()->json($transactions);
    }

    public function create(MakeTransactionRequest $request)
    {
        $request->make();
        return response()->json([
            'message' => 'The transaction has been saved!'
        ]);
    }

    public function postTransaction(Request $request)
    {
        $request->validate([
            'transaction_id' => 'integer|exists:transactions,id|required'
        ]);

        $transaction = Transaction::find($request->transaction_id);
        if($transaction->user_id !== Auth::id()){
            throw ValidationException::withMessages([
                'transaction_id' => 'This transaction does not exist.'
            ]);
        }

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
