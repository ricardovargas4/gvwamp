<?php

namespace gv\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificaDesenvolvedor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }
        */
        $nivel = Auth::user()->nivel;
        if($nivel>1){
            return redirect('/home');
        }
        return $next($request);
    }
}
