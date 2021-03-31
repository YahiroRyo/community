<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostImageNamesTable extends Migration
***REMOVED***
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    ***REMOVED***
        Schema::create('post_image_names', function (Blueprint $table) ***REMOVED***
            $table->integer('post_id');
            $table->string('image_name', 100);
        ***REMOVED***);
    ***REMOVED***

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    ***REMOVED***
        Schema::dropIfExists('post_image_names');
    ***REMOVED***
***REMOVED***
