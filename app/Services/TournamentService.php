<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\Player;
use App\Models\Game;

class TournamentService
{
    protected $tournament;

    protected $games;

    protected $save;

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
        $this->save = is_null($players);
        $players = $players ? $players : $this->tournament->players;

        if (!self::isPowerOf2($players->count())) {
            throw new \Exception("Players number isn't power of 2", 1);
        }

        $this->games = collect();

        $this->play($players);

        if ($this->save) {
            $this->tournament->fill([
                'winner' => Player::whereName($this->games->last()['winner'])
                    ->firstOrFail()->id
            ])->save();
        }

        return $this->games;
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

        $this->games->push([
            'player1' => [
                'name' => $player1->name,
                'score' => $player1Score,
            ],
            'player2' => [
                'name' => $player2->name,
                'score' => $player2Score,
            ],
            'winner' => $winner->name
        ]);

        if ($this->save) {
            Game::create([
                'tournament_id' => $this->tournament->id,
                'player1' => $player1->id,
                'score1' => $player1Score,
                'player2' => $player2->id,
                'score2' => $player2Score,
            ]);
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
