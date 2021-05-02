<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
***REMOVED***
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    ***REMOVED***
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    ***REMOVED***
***REMOVED***
