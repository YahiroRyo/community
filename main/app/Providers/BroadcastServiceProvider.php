<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
***REMOVED***
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    ***REMOVED***
        Broadcast::routes();

        require base_path('routes/channels.php');
    ***REMOVED***
***REMOVED***
