<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\Player;

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
        if (!self::isPowerOf2($this->tournament->players->count())) {
            throw new \Exception("Players number isn't power of 2", 1);
        }

        return $this->play($this->tournament->players);
    }

    public function play($players)
    {
        if ($players->count() === 2) {
            return $this->getMatchWinner($players->first(), $players->last());
        } else {
            $zones = $players->split(2);
            return $this->getZoneWinner($zones->first(), $zones->last());
        }
    }

    public function getMatchWinner($player1, $player2)
    {
        return $this->getScore($player1) >= $this->getScore($player2) ? $player1 : $player2;
    }

    public function getZoneWinner($zone1, $zone2)
    {
        return $this->getMatchWinner($this->play($zone1), $this->play($zone2));
    }

    public function getScore($player)
    {
        $playerParameters = $player->pivot;
        return ($playerParameters->skill_level * 40 +
            $playerParameters->strength * 20 +
            $playerParameters->speed * 20 +
            rand(0, 100) * 20
        ) / 100;
    }
}
