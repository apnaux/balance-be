<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(AccountRequest $request)
    {
        $accounts = $request->retrieve();
        return response()->json($accounts);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'type' => 'string|required',
            'limit' => 'numeric|nullable',
            'statement_date' => 'integer|nullable',
            'days_before_due' => 'integer|nullable'
        ]);

        $account = new Account();
        $account->name = $request->name;
        $account->type = $request->type;
        $account->limit = $request->limit;
        $account->statement_date = $request->statement_date;
        $account->days_before_due = $request->days_before_due;

        $account->user()->associate(Auth::user());
        $account->save();

        return response()->json([
            'message' => 'The account has been saved!'
        ]);
    }
}
