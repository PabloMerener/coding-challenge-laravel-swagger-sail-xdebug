<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\Player;

class TournamentService
{
    protected $tournament;

    protected $games;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    public static function isPowerOf2(int $number)
    {
        return ($number & ($number - 1)) === 0;
    }

    public function run($players = null)
    {
        $players = $players ? $players : $this->tournament->players;

        if (!self::isPowerOf2($players->count())) {
            throw new \Exception("Players number isn't power of 2", 1);
        }

        $this->games = [];

        $this->play($players);

        return $this->games;
    }

    public function play($players)
    {
        if ($players->count() === 2) {
            return $this->getMatchWinner($players->first(), $players->last());
        } else {
            $zones = $players->split(2);
            return $this->getMatchWinner($this->play($zones->first()), $this->play($zones->last()));
        }
    }

    public function getMatchWinner($player1, $player2)
    {
        $player1Score = $this->getScore($player1);
        $player2Score = $this->getScore($player2);
        $winner = $player1Score >= $player2Score ? $player1 : $player2;

        $this->games[] = [
            'player1' => [
                'name' => $player1->name,
                'score' => $player1Score,
            ],
            'player2' => [
                'name' => $player2->name,
                'score' => $player2Score,
            ],
            'winner' => $winner->name
        ];

        return $winner;
    }

    public function getScore($player)
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
