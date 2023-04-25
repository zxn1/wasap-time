<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatmessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatmessages', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id')->nullable();
            $table->string('from_id')->nullable();
            $table->string('chat_message')->nullable();
            $table->timestamps();
        });

        Schema::table('chatmessages', function (Blueprint $table) {
            $table->foreign('from_id')->references('session_id')->on('rand_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatmessages');
    }
}
