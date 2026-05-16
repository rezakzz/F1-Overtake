@extends('layouts.front')
@section('title', $heroData['title']) 

@section('content')
@include('components.navbar')
    <section class="hero">
        @component('components.katalog_content')
            @slot('cover', asset($heroData['cover'])) 
            @slot('title', $heroData['title'])
            @slot('subtitle', $heroData['subtitle'])
            @slot('type', $heroData['type'])
        @endcomponent
    </section>
    <main class="container-katalog">
        <section class="product-section">
            <div class="product-grid swipe-row">
                @forelse($products as $product)
                    @component('components.product_card')
                        @slot('id', $product->id)
                        @slot('cover', asset($product->cover)) 
                        @slot('category', $product->category ?? 'Merchandise') 
                        @slot('name', $product->name)
                        @slot('price', 'Rp ' . number_format($product->price, 0, ',', '.'))
                    @endcomponent
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Belum ada produk untuk tim ini.</p>
                    </div>
                @endforelse
                
            </div>
        </section>
    </main>

    @include('components.footer')

@endsection