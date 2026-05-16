<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Product;

class F1Seeder extends Seeder
{
    public function run()
    {
        $teams = [
            [
                'name' => 'Scuderia Ferrari',
                'slug' => 'Ferrari',
                'color' => '#DC0000',
                'logo' => 'images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png',
                'background' => 'images/ferrari_bg.jpg'
            ],
            [
                'name' => 'Mercedes-AMG F1',
                'slug' => 'Mercedes',
                'color' => '#00D2BE',
                'logo' => 'images/Mercedes-Logo.svg.png',
                'background' => 'images/mercedes_bg.jpg'
            ],
            [
                'name' => 'Red Bull Racing',
                'slug' => 'RedBull',
                'color' => '#061D42',
                'logo' => 'images/redbull.png',
                'background' => 'images/redbull_bg.jpg'
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
        Product::create([
            'name' => 'Kaus Polo Scuderia Ferrari 2025',
            'category' => 'Apparel',
            'price' => 1450000,
            'cover' => 'images/Kaus-Polo-Scuderia-Ferrari-2025-Team-Pria.jpg',
            'team_slug' => 'Ferrari'
        ]);

        Product::create([
            'name' => 'Topi Max Verstappen 2025',
            'category' => 'Aksesoris',
            'price' => 990000,
            'cover' => 'images/topimax.jpg',
            'team_slug' => 'RedBull'
        ]);
    }
}
