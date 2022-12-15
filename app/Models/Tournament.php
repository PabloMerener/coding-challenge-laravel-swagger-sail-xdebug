<?php

namespace App\Models;

use App\Services\TournamentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $service;

    public function __construct()
    {
        $this->service = new TournamentService($this);
    }

    public function winner()
    {
        $this->belongsTo(Player::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)
            ->withPivot(
                'skill_level',
                'strength',
                'speed',
                'reaction_time'
            );
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
