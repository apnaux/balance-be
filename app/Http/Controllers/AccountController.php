<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function list(AccountRequest $request)
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
        $account->limit = $request->type == 'credit' ? $request->limit : null;
        $account->statement_date = $request->type == 'credit' ? $request->statement_date : 1;
        $account->days_before_due = $request->type == 'credit' ? $request->days_before_due : null;

        $account->user()->associate(Auth::user());
        $account->save();

        return response()->json([
            'message' => 'The account has been saved!'
        ]);
    }
}
