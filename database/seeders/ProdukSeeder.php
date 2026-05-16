<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\produks;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        produks::create([
            'name' => 'Kaus Polo Scuderia Ferrari 2025 Team Pria',
            'category' => 'Polo Shirt',
            'price' => 1450000,
            'stock' => 10,
            'cover' => 'images/Kaus-Polo-Scuderia-Ferrari-2025-Team-Pria.jpg',
            'team_slug' => 'ferrari',
            'description' => 'Polo Tim Scuderia Ferrari 2025 resmi dari PUMA, yang dikenal karena dikenakan sepanjang musim Formula 1 2025 oleh tim dan pembalap Lewis dan Charles',
        ]);

        produks::create([
            'name' => 'Topi Max Verstappen 2025',
            'category' => 'Caps',
            'price' => 990000,
            'stock' => 10,
            'cover' => 'images/topimax.jpg',
            'team_slug' => 'redbull-racing',
            'description' => 'Topi Pembalap Red Bull Racing 2025 resmi Max Verstappen, dirancang dan diproduksi oleh mitra resmi ORBR, New Era. Dikenakan selama akhir pekan balapan F1, Topi Snap Stretch New Era 9SEVENTY® Max yang berfokus pada performa menampilkan detail Verstappen di bagian depan, plus branding Red Bull Racing dan New Era untuk musim 2025.',
        ]);

        produks::create([
            'name' => '2025 George Russell W16 1:43 Scale Model',
            'category' => 'Colletibles',
            'price' => 1800000,
            'stock' => 10,
            'cover' => 'images/2025GeorgeRussellW16143ScaleModel.webp',
            'team_slug' => 'mercedes',
            'description' => 'Model koleksi resmi berlisensi skala 1:43 dari mobil George Russell 2025 Mercedes-AMG Petronas Formula One Team W16. Diproduksi di bawah lisensi dengan detail yang sangat teliti oleh pakar model Spark, model ini terpasang pada alas dengan kotak pajangan transparan.',
        ]);

        produks::create([
            'name' => 'Puma 2025 Team Softshell Jacketa',
            'category' => 'Jackets',
            'price' => 1450000,
            'stock' => 10,
            'cover' => 'images/Puma2025TeamSoftshellJacket.jpg',
            'team_slug' => 'ferrari',
            'description' => 'Jaket Softshell Tim Scuderia Ferrari 2025 resmi dari PUMA, yang mudah dikenali karena terinspirasi dari tampilan yang dikenakan tim sepanjang musim Formula 1 2025. Jaket Softshell Tim ini hadir dalam warna Merah Scuderia Ferrari 2025, dengan saku samping beritsleting dan semua logo resmi Ferrari dan PUMA.',
        ]);
    }
}
