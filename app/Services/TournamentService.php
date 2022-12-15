<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Support\Collection;

class TournamentService
{
    protected $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * isPowerOf2
     *
     * @param  int  $number
     * @return bool
     */
    public static function isPowerOf2(int $number): bool
    {
        return ($number & ($number - 1)) === 0;
    }

    /**
     * run
     *
     * @return Collection
     */
    public function run(): Collection
    {
        if ($this->tournament->players->count() < 2) {
            throw new \Exception('Wrong players number', 1);
        } elseif (! self::isPowerOf2($this->tournament->players->count())) {
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

    /**
     * play
     *
     * @param  Collection  $players
     * @return Player
     */
    private function play(Collection $players): Player
    {
        if ($players->count() === 2) {
            return $this->getMatchWinner($players->first(), $players->last());
        } else {
            $zones = $players->split(2);

            return $this->getMatchWinner($this->play($zones->first()), $this->play($zones->last()));
        }
    }

    /**
     * getMatchWinner
     *
     * @param  Player  $player1
     * @param  Player  $player2
     * @return Player
     */
    private function getMatchWinner(Player $player1, Player $player2): Player
    {
        $player1Score = $this->getScore($player1);
        $player2Score = $this->getScore($player2);
        $winner = $player1Score >= $player2Score ? $player1 : $player2;

        $game = new Game([
            ...$this->tournament->exists
                ? ['player1' => $player1->id, 'player2' => $player2->id, 'tournament_id' => $this->tournament->id]
                : ['player1' => $player1->name, 'player2' => $player2->name],
            'score1' => $player1Score,
            'score2' => $player2Score,
        ]);

        if ($this->tournament->exists) {
            $game->save();
        } else {
            $this->tournament->games->push($game);
        }

        return $winner;
    }

    /**
     * getScore
     *
     * @param  Player  $player
     * @return float
     */
    private function getScore(Player $player): float
    {
        $parameters = $player->pivot;

        $score = $parameters->skill_level * 40 + rand(0, 100) * 20;

        $score += $this->tournament->gender === 'male' ?
            $parameters->strength * 20 + $parameters->speed * 20 :
            $parameters->reaction_time * 40;

        return $score / 100;
    }
}
