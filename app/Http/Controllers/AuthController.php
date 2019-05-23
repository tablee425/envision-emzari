<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = auth()->attempt(['email' => $request->email, 'password' => $request->password]);
        if($user) {
            return redirect('dashboard');
        } else {
            return redirect()->back();
        }
    }
}
