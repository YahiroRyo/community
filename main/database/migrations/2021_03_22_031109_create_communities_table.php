<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunitiesTable extends Migration
***REMOVED***
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    ***REMOVED***
        Schema::create('communities', function (Blueprint $table) ***REMOVED***
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('name', 50);
            $table->string('description', 500);
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
        Schema::dropIfExists('communities');
    ***REMOVED***
***REMOVED***
