<?php

namespace App\Http\Controllers\Acess;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AcessController extends Controller {
    
    public function logon(Request $request) {

        $credentials = $request->only(['email', 'password']);
        $credentials['password'] = $credentials['password'];
        if (Auth::attempt($credentials)) {
            return redirect()->route('app');
        } else {
            return redirect()->back()->with('error', 'Credenciais inválidas!');
        }
    }

    public function logout() {
        
        Auth::logout();
        return redirect()->route('login');
    }

    public function forgoutPassword($code = null) {

        return view('forgout-password', ['code' => $code]);
    }

    public function registerUserExternal($id, $grupo = null) {

        return view('Form.registerUser', ['id' => $id, 'grupo' => $grupo]);
    }

}
