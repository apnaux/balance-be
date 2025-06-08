<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeTransactionRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
}
