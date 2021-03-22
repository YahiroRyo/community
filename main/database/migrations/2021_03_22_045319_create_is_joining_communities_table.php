<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsJoiningCommunitiesTable extends Migration
***REMOVED***
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    ***REMOVED***
        Schema::create('is_joining_communities', function (Blueprint $table) ***REMOVED***
            $table->integer('user_id')->unsigned();
            $table->integer('community_id')->unsigned();
        ***REMOVED***);
    ***REMOVED***

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    ***REMOVED***
        Schema::dropIfExists('is_joining_communities');
    ***REMOVED***
***REMOVED***
