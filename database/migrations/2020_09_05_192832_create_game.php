<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game', function (Blueprint $table) {
            $table->id();
            $table->string("playerName")->nullable();
            $table->integer("delay")->nullable();
            $table->json('deck')->nullable();
            $table->json('player_deck')->nullable();
            $table->json('dealer_deck')->nullable();
            $table->integer('player_point')->nullable();
            $table->integer('dealer_point')->nullable();
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
        Schema::dropIfExists('game');
    }
}
