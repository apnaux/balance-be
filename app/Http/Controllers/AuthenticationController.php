<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'string|max:8|required|unique:users,username',
            'password' => 'min:8|required',
        ]);

        User::create([
            'username' => $request->username,
            'password' => $request->password
        ]);

        if (str_contains($request->path(), 'api')) {
            return response()->json([
                'message' => 'Your account has been created. You may now log in!'
            ]);
        }

        Auth::attempt($request->only('username', 'password'));
        $request->session()->regenerate();

        return redirect()->intended('hello');
    }

    public function authenticate(AuthenticationRequest $request)
    {
        if (str_contains($request->path(), 'api')) {
            $token = $request->createToken();
            $user = Auth::user();

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        if($request->authenticate()){
            return redirect()->intended('/hello');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function revoke(Request $request)
    {
        if (str_contains($request->path(), 'api')) {
            $request->validate([
                'token_id' => 'required'
            ]);

            $request->user()->tokens()->where('id', $request->token_id)->delete();

            return response()->json([
                'message' => 'You are now logged out.'
            ]);
        }

        Auth::logout();
        return redirect()->route('login');
    }
}
