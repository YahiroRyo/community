<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
***REMOVED***
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    ***REMOVED***
        if (! $request->expectsJson()) ***REMOVED***
            return route('login');
        ***REMOVED***
    ***REMOVED***
***REMOVED***
