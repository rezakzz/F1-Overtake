<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\table_teams;

class TeamSeeder extends Seeder
{
    public function run()
    {
        table_teams::create([
            'name' => 'Ferrari',
            'slug'=>'Ferrari',
            'color' => '#DC0000',
            'logo' => 'images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png',
            'background' => 'images/pexels-samuel-phillips-2148550424-30205195.jpg',
        ]);
        table_teams::create([
            'name' => 'Mercedes',
            'slug'=>'mercedes',
            'color' => '#00D2BE;',
            'logo' => 'images/Mercedes-Logo.svg.png',
            'background' => 'images/pexels-jonathanborba-29382691.jpg',
        ]);
        table_teams::create([
            'name' => 'RedBull Racing',
            'slug'=>'red-bull-racing',
            'color' => '#FDD900',
            'logo' => 'images/redbull.png',
            'background' => 'images/2025-Formula1-Red-Bull-Racing-RB21-001-2000.jpg',
        ]);
        table_teams::create([
            'name' => 'McLaren',
            'slug'=>'mclaren',
            'color' => '#FF8700',
            'logo' => 'images/mclaren.png',
            'background' => 'images/pexels-jonathanborba-29320670.jpg',
        ]);
        table_teams::create([
            'name' => 'Williams Racing',
            'slug'=>'williams-racing',
            'color' => '#1868DB',
            'logo' => 'images/williams.png',
            'background' => 'images/pexels-exactissime-30054758.jpg',
        ]);
        table_teams::create([
            'name' => 'Aston Martin',
            'slug'=>'aston-martin',
            'color' => '#002420',
            'logo' => 'images/astonmartin.png',
            'background' => 'images/pexels-jonathanborba-29309756.jpg',
        ]);
        table_teams::create([
            'name' => 'Racing Bulls RB',
            'slug'=>'racingbulls-rb',
            'color' => '#000D8D',
            'logo' => 'images/racingbullsrb.png',
            'background' => 'images/2025-Formula1-Racing-Bulls-RB02-001-2000.jpg',
        ]);
        table_teams::create([
            'name' => 'Alpine',
            'slug'=>'alpine',
            'color' => '#2673E2',
            'logo' => 'images/alpine.png',
            'background' => 'images/2024-Formula1-Alpine-A524-006-2000.jpg',
        ]);
        table_teams::create([
            'name' => 'Haas',
            'slug'=>'haas',
            'color' => '#E6002B',
            'logo' => 'images/haas.png',
            'background' => 'images/pexels-fero-19240678.jpg',
        ]);
        table_teams::create([
            'name' => 'Kick Sauber',
            'slug'=>'kicksauber',
            'color' => '#A0E52F',
            'logo' => 'images/kicksauber.png',
            'background' => 'images/kicksauberBG.webp',
        ]);
    }
}
