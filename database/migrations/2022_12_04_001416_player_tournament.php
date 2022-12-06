<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_tournament', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained();
            $table->foreignId('player_id')->constrained();
            $table->unsignedSmallInteger('skill_level');
            // Male parameters
            $table->unsignedSmallInteger('strength')->nullable();
            $table->unsignedSmallInteger('speed')->nullable();
            // Female parameters
            $table->unsignedSmallInteger('reaction_time')->nullable();

            $table->unique(['tournament_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_tournament');
    }
};
