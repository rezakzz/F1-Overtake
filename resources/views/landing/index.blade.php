@extends('layouts.front')
@section('title', 'Home Page')
@section('content')
@include('components.navbar')

    @if(session('success') || session('error'))
    <div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    </div>
    @endif
    <section class="hero">
        <div class="hero-content">
            <span class="hero-subtitle">KOLEKSI MUSIM 2025</span>
            <h1 class="hero-title">AUTHENTIC TEAM GEAR</h1>
            <p class="hero-description">Rasakan kecepatan. Kenakan kebanggaan.</p>
        </div>
        
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="{{ asset('/images/vid_bg2.MP4') }}" type="video/mp4">
        </video>

        <div class="hero-overlay"></div>

    </section>

    <main class="container">
        <section class="team-section" id="teams">
            <h2 class="section-title">F1 Racing Teams</h2>
            <div class="team-grid swipe-row">
                @foreach($teams as $team)
                    @include('components.team_card', [
                        'name' => $team->name,      
                        'color' => $team->color,
                        'logo' => asset($team->logo),
                        'slug' => $team->slug
                    ])
                @endforeach
            </div>
        </section>

        <section class="product-section">
            <h2 class="section-title">Produk Terlaris</h2>
        
            <div class="product-grid swipe-row">
                @forelse($bestSellers as $product)
                    @include('components.product_card', [
                        'id' => $product->id,
                        'name' => $product->name,
                        'category' => $product->category,
                        'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                        'cover' => $product->cover
                    ])
                @empty
                    <p class="text-muted text-center">
                        Belum ada produk terlaris.
                    </p>
                @endforelse
            </div>
        </section>

        <section class="product-section">
            <h2 class="section-title">Driver Populer</h2>
            
            <div class="product-grid swipe-row">
                @foreach($drivers as $driver)        
                    @component('components.driver_card')
                        @slot('cover', asset($driver->image_path)) 
                        @slot('team', $driver->team) 
                        @slot('name', $driver->name) 
                        @slot('slug', \Illuminate\Support\Str::slug($driver->team))
                    @endcomponent  
                @endforeach
            </div>
        </section>
    </main>
    @include('components.footer')
    
@endsection