<?php

namespace App\Http\Controllers\API;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
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
            'username' => 'string|max:8|required|unique:users,username',
            'password' => 'max:12|required'
        ]);

        User::create([
            'username' => $request->username,
            'password' => $request->password
        ]);

        return response()->json([
            'message' => 'Your account has been created. You may now log in!'
        ]);
    }

    public function authenticateViaAPI(AuthenticationRequest $request)
    {
        $token = $request->createToken();

        return response()->json([
            'token' => $token
        ]);
    }

    public function revokeViaAPI(Request $request)
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
