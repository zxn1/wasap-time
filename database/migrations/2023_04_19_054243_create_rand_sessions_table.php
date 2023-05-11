<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRandSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('rand_sessions', function (Blueprint $table) {
            $table->string('session_id')->primary();
            $table->timestamp('last_activity')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        //sini tak boleh nak buat foreign - sebab ada system id '1111111111'
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
        Schema::dropIfExists('rand_sessions');
    }
}
