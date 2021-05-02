<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
***REMOVED***
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    ***REMOVED***
        Schema::create('user_infos', function (Blueprint $table) ***REMOVED***
            $table->integer('user_id')->unsigned();
            $table->string('name', 30);
            $table->string('user_name', 30);
            $table->string('image_name', 100)->default('default.jpg');
            $table->string('intro', 200)->nullable();
        ***REMOVED***);
    ***REMOVED***

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    ***REMOVED***
        Schema::dropIfExists('user_infos');
    ***REMOVED***
***REMOVED***
