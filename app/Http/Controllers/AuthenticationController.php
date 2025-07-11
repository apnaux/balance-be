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
            'password' => 'min:8|required|confirmed',
        ]);

        User::create([
            'username' => $request->username,
            'password' => $request->password
        ]);

        Auth::attempt($request->only('username', 'password'));
        $request->session()->regenerate();

        return redirect()->intended('configuration');
    }

    public function authenticate(AuthenticationRequest $request)
    {
        if($request->authenticate()){
            return redirect()->intended('/budgets');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function revoke(Request $request)
    {
        Auth::logout();

        return route('login');
    }
}
