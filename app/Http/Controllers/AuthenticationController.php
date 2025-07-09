<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Requests\AuthenticationRequest;
use App\Models\User;
use App\Models\UserOption;
use App\Models\UserTransactionCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'user_name' => 'string|max:8|required|unique:users,user_name',
            'password' => 'max:12|required'
        ]);

        User::create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'password' => $request->password
        ]);

        return response()->json([
            'message' => 'Your account has been created. You may now log in!'
        ]);
    }

    public function setOptions(Request $request)
    {
        $request->validate([
            'currency' => 'string|nullable',
            'cycle_cutoff' => 'integer|max:31|nullable',
            'allocated_budget' => 'numeric|nullable',
            'timezone' => 'string|nullable',
        ]);

        User::find(Auth::id())->option()
            ->updateOrCreate(['user_id' => Auth::id()], $request->all());

        $latestCycle = UserTransactionCycle::where('user_id', $request->user()->id)->orderByDesc('active_from')->first();

        if(filled($latestCycle) && filled($request->cycle_cutoff)) {
            $options = UserOption::where('user_id', Auth::id())->first();
            $latestCycle->update([
                'active_until' => Utils::getProperStatementDate($options->timezone, $request->cycle_cutoff)->addMonth()->toIso8601String()]
            );
        }

        if(filled($latestCycle) && filled($request->allocated_budget)) {
            $latestCycle->update(['allocated_budget' => $request->allocated_budget]);
        }

        return response()->json([
            'message' => 'Your options has been updated!'
        ]);
    }

    public function authenticate(AuthenticationRequest $request)
    {
        $token = $request->authenticateUser();

        return response()->json([
            'token' => $token
        ]);
    }

    public function revoke(Request $request)
    {
        $request->validate([
            'token_id' => 'required'
        ]);

        $request->user()->tokens()->where('id', $request->token_id)->delete();

        return response()->json([
            'message' => 'You are now logged out.'
        ]);
    }
}
