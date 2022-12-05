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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained();
            $table->foreignId('player1')->constrained('players');
            $table->decimal('score1', $precision = 5, $scale = 2);
            $table->foreignId('player2')->constrained('players');
            $table->decimal('score2', $precision = 5, $scale = 2);
            $table->timestamps();

            $table->unique(['tournament_id', 'player1', 'player2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
};
