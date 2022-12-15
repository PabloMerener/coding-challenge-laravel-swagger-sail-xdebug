<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolandGarros77 = Tournament::create([
            'name' => 'Roland Garros',
            'gender' => 'male',
            'date' => '1977-05-23',
        ]);

        $players = [
            'Ilie Năstase' => ['skill_level' => 40, 'strength' => 40, 'speed' => 40],
            'Brian Gottfried' => ['skill_level' => 90, 'strength' => 90, 'speed' => 90],
            'Phil Dent' => ['skill_level' => 70, 'strength' => 70, 'speed' => 70],
            'José Higueras' => ['skill_level' => 30, 'strength' => 30, 'speed' => 30],
            'Wojtek Fibak' => ['skill_level' => 60, 'strength' => 60, 'speed' => 60],
            'Guillermo Vilas' => ['skill_level' => 100, 'strength' => 100, 'speed' => 100],
            'Raúl Ramírez' => ['skill_level' => 80, 'strength' => 80, 'speed' => 80],
            'Adriano Panatta' => ['skill_level' => 50, 'strength' => 50, 'speed' => 50],
        ];

        foreach ($players as $name => $playerParameters) {
            $rolandGarros77->players()->attach(
                Player::whereName($name)->firstOrFail()->id,
                $playerParameters
            );
        }

        $rolandGarros77->service->run();
    }
}
