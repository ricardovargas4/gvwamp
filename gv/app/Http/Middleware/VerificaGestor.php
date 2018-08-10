<?php

namespace gv\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificaGestor
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

        $nivel = Auth::user()->nivel;
        if($nivel>2){
            return redirect('/home');
        }
        return $next($request);
    }
}
