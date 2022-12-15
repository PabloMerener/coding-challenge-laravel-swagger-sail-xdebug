<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $players = [
            'Ilie Năstase',
            'Brian Gottfried',
            'Phil Dent',
            'José Higueras',
            'Wojtek Fibak',
            'Guillermo Vilas',
            'Raúl Ramírez',
            'Adriano Panatta',
        ];

        foreach ($players as $key => $name) {
            Player::create([
                'name' => $name,
                'gender' => 'male',
            ]);
        }
    }
}
