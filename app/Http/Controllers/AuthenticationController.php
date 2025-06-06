<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Models\User;
use Illuminate\Http\Request;

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
