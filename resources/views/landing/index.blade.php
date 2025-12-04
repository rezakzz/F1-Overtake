@extends('layouts.front')
@section('title', 'Home Page')
@section('content')
@include('components.navbar')
    <section class="hero">
        <div class="hero-content">
            <span class="hero-subtitle">KOLEKSI MUSIM 2025</span>
            <h1 class="hero-title">AUTHENTIC TEAM GEAR</h1>
            <p class="hero-description">Rasakan kecepatan. Kenakan kebanggaan.</p>
            <a href="{{route ('landing.Katalog', 'Formula 1 Teams') }}" class="btn btn-primary">Belanja Sekarang</a>
        </div>
        <div class="hero-background-image" style="background-image: url(images/background.jpg);"></div>
    </section>

    <main class="container">
        <section class="team-section">
            <h2 class="section-title">F1 Racing Teams</h2>
            <div class="team-grid">
                @component('components.team_card')
                    @slot('logo', 'images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png')
                    @slot('name', 'Ferrari')
                    @slot('color', '#DC0000')
                @endcomponent
            </div>
        </section>

        <section class="product-section">
            <h2 class="section-title">Produk Terlaris</h2>
            <div class="product-grid">
                @component('components.product_card')
                    @slot('cover', 'images/Kaus-Polo-Scuderia-Ferrari-2025-Team-Pria.jpg')
                    @slot('category', 'Polo Shirt')
                    @slot('name', 'Kaus Polo Scuderia Ferrari 2025 Team Pria')
                    @slot('price', 'Rp 1.450.000')
                @endcomponent
            </div>
        </section>

        <section class="product-section">
            <h2 class="section-title">Driver Populer</h2>
            <div class="product-grid">
                @component('components.driver_card')
                    @slot('cover', 'images/charlesleclerc.jpg')
                    @slot('team', 'Ferrari')
                    @slot('name', 'Charles Leclerc')
                @endcomponent
            </div>
        </section>
    </main>
    @include('components.footer')
    
@endsection