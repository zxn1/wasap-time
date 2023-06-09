<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRtcSignallingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('rtc_signallings', function (Blueprint $table) {
            $table->id();
            $table->string('from_id')->nullable();
            $table->string('to_id')->nullable();
            $table->longtext('sdp_offer')->nullable();
            $table->longtext('sdp_answer')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rtc_signallings');
    }
}
