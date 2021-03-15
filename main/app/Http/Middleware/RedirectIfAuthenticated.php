<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
***REMOVED***
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    ***REMOVED***
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) ***REMOVED***
            if (Auth::guard($guard)->check()) ***REMOVED***
                return redirect(RouteServiceProvider::HOME);
            ***REMOVED***
        ***REMOVED***

        return $next($request);
    ***REMOVED***
***REMOVED***
