<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
***REMOVED***
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array
     */
    public function hosts()
    ***REMOVED***
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    ***REMOVED***
***REMOVED***
