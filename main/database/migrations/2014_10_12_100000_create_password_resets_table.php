<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
***REMOVED***
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    ***REMOVED***
        Schema::create('password_resets', function (Blueprint $table) ***REMOVED***
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        ***REMOVED***);
    ***REMOVED***

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    ***REMOVED***
        Schema::dropIfExists('password_resets');
    ***REMOVED***
***REMOVED***
