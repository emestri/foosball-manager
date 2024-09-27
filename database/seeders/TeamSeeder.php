<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::all()->chunk(2) as $users) {
            if($users->count() === 2) {
                Team::create([
                    'player_one_id' => $users->first()->id,
                    'player_two_id' => $users->last()->id,
                ]);
            }
        }
    }
}
