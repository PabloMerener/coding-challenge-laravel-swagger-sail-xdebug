<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Tournament;

class TournamentService
{
    protected $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public static function isPowerOf2(int $number)
    {
        return ($number & ($number - 1)) === 0;
    }

    public function run()
    {
        if (! self::isPowerOf2($this->tournament->players->count())) {
            throw new \Exception("Players number isn't power of 2", 1);
        }

        $winner = $this->play($this->tournament->players);

        if ($this->tournament->exists) {
            $this->tournament->fill(['winner' => $winner->id])->save();
        } else {
            $this->tournament->winner = $winner->name;
        }

        return $this->tournament->games;
    }

    private function play($players)
    {
        if ($players->count() === 2) {
            return $this->getMatchWinner($players->first(), $players->last());
        } else {
            $zones = $players->split(2);

            return $this->getMatchWinner($this->play($zones->first()), $this->play($zones->last()));
        }
    }

    private function getMatchWinner($player1, $player2)
    {
        $player1Score = $this->getScore($player1);
        $player2Score = $this->getScore($player2);
        $winner = $player1Score >= $player2Score ? $player1 : $player2;

        $game = new Game([
            'tournament_id' => $this->tournament->id,
            'player1' => $this->tournament->exists ? $player1->id : $player1->name,
            'score1' => $player1Score,
            'player2' => $this->tournament->exists ? $player2->id : $player2->name,
            'score2' => $player2Score,
        ]);

        if ($this->tournament->exists) {
            $game->save();
        } else {
            $this->tournament->games->push($game);
        }

        return $winner;
    }

    private function getScore($player)
    {
        $parameters = $player->pivot;

        $score = $parameters->skill_level * 40 + rand(0, 100) * 20;

        if ($this->tournament->gender === 'male') {
            return ($score + $parameters->strength * 20 + $parameters->speed * 20) / 100;
        } else {
            return ($score + $parameters->reaction_time * 40) / 100;
        }
    }
}
