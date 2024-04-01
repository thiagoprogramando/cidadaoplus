<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType {
    
    public function handle($request, Closure $next, $type) {
        if (Auth::check()) {

            $user = Auth::user();
            if ($user->tipo == $type) {
                Auth::logout();
                
                return redirect()->route('login')->with('error', 'Você não tem permissão para acessar o ambiente!');
            }
        }
        
        return $next($request);
    }
}
