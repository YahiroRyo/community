<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCanIJoinCommunities extends Migration
***REMOVED***
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    ***REMOVED***
        Schema::create('can_i_join_communities', function (Blueprint $table) ***REMOVED***
            $table->integer('bell_id')->unsigned();
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
        Schema::dropIfExists('can_i_join_communities');
    ***REMOVED***
***REMOVED***
