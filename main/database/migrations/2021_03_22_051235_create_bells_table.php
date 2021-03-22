<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBellsTable extends Migration
***REMOVED***
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    ***REMOVED***
        Schema::create('bells', function (Blueprint $table) ***REMOVED***
            $table->id();
            $table->integer('type')->unsigned();
            $table->timestamps();
        ***REMOVED***);
    ***REMOVED***

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    ***REMOVED***
        Schema::dropIfExists('bells');
    ***REMOVED***
***REMOVED***
