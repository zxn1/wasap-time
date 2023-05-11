<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChattingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('chattings', function (Blueprint $table) {
            $table->id();
            $table->string('from_id')->nullable();
            $table->string('messages');
            $table->timestamps();
        });

        // Schema::table('chattings', function (Blueprint $table) {
        //     $table->foreign('from_id')->references('session_id')->on('rand_sessions');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chattings');
    }
}
