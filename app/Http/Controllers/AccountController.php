<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\UserOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function list(Request $request)
    {
        if ($request->input('selection', false)) {
            $accounts = Account::select(['name as label', 'id as value'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $accounts = Account::where('user_id', Auth::id())->get();
        }

        return response()->json($accounts);
    }

    public function create(Request $request)
    {
        $options = UserOption::where('user_id', Auth::id())->first();
        $statement_date = $request->statement_date ? Carbon::parse($request->statement_date, $options->timezone)->timezone('UTC')->toDateTimeString(): null;
        $due_date = $request->due_date ? Carbon::parse($request->due_date, $options->timezone)->timezone('UTC')->toDateTimeString(): null;

        Account::create([
            'user_id' => Auth::id(),
            "name" => $request->input('name'),
            'type' => $request->input('type'),
            'currency' => $options->currency,
            'limit' => $request->input('limit'),
            'statement_date' => $statement_date,
            'due_date' => $due_date
        ]);

        return response()->json([
            'message' => 'Account has been created'
        ]);
    }

    public function update(Request $request)
    {
        $options = UserOption::where('user_id', Auth::id())->first();
        $account = Account::where('user_id', Auth::id())
            ->where('id', $request->input('id'))
            ->first();

        // Parse statement/due dates if applicable
        $statement_date = $request->statement_date ? Carbon::parse($request->statement_date, $options->timezone)->timezone('UTC')->toDateTimeString(): null;
        $due_date = $request->due_date ? Carbon::parse($request->due_date, $options->timezone)->timezone('UTC')->toDateTimeString(): null;

        $account->name = $request->input('name', $account->name);
        $account->type = $request->input('type', $account->type);
        $account->limit = $request->input('limit', $account->limit);
        $account->statement_date = $statement_date ?? $account->statement_date;
        $account->due_date = $due_date ?? $account->due_date;

        return response()->json([
            'message' => 'Account has been edited'
        ]);
    }
}
