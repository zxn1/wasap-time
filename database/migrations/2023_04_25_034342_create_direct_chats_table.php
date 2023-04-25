<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_chats', function (Blueprint $table) {
            $table->id();
            $table->string('from_id')->nullable();
            $table->string('to_id')->nullable();
            $table->string('chatid')->nullable();
            $table->timestamps();
        });

        Schema::table('direct_chats', function (Blueprint $table) {
            $table->foreign('from_id')->references('session_id')->on('rand_sessions');
            $table->foreign('to_id')->references('session_id')->on('rand_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direct_chats');
    }
}
