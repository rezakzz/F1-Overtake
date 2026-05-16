<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    public function run()
    {
        Driver::create([
            'name' => 'Max Verstappen',
            'team' => 'Red Bull Racing',
            'team_slug' => 'red-bull-racing',
            'image_path' => 'images/maxverstappen.jpg',
        ]);
        Driver::create([
            'name' => 'Charles Leclerc',
            'team' => 'Ferrari',
            'team_slug' => 'ferrari',
            'image_path' => 'images/charlesleclerc.jpg',
        ]);
        Driver::create([
            'name' => 'Lando Norris',
            'team' => 'McLaren',
            'team_slug' => 'mclaren',
            'image_path' => 'images/landonorris.jpg',
        ]);
        Driver::create([
            'name' => 'Lewis Hamilton',
            'team' => 'Ferrari',
            'team_slug' => 'ferrari',
            'image_path' => 'images/lewishamilton.jpg',
        ]);
    }
}
