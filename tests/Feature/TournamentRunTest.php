<?php

namespace Tests\Feature;

use App\Models\Tournament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentRunTest extends TestCase
{
    use RefreshDatabase;

    public function test_games()
    {
        $this->seed();

        $tournament = Tournament::first();

        $this->assertEquals($tournament->players->count(), 8);
        $this->assertEquals($tournament->games->count(), 7);

        $this->assertEquals(
            $tournament
                ->games
                ->filter(
                    fn ($e) => $e->player1 === $tournament->winner || $e->player2 === $tournament->winner
                )
                ->count(),
            3 // matches played by the tournament winner
        );
    }
}
