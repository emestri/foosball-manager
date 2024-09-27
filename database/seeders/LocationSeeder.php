<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::factory()->create(['name' => 'Würzburg']);
        Location::factory()->create(['name' => 'Berlin']);
    }
}
