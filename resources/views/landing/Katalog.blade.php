@extends('layouts.front')
@section('title', 'Katalog')
@section('content')
@include('components.navbar')
    <section class="hero">
        @component('components.katalog_content')
            @slot('cover', 'images/pexels-abed-ismail-6697875.jpg')
            @slot('title', 'Formula 1 Teams')
            @slot('subtitle', 'F1 Racing Teams')
            @slot('type', 'Merchandise')
        @endcomponent
    </section>
    
    <main class="container-katalog">
        <section class="product-section">
            <div class="product-grid">
    
                @component('components.product_card')
                    @slot('cover', 'images/Kaus-Polo-Scuderia-Ferrari-2025-Team-Pria.jpg')
                    @slot('category', 'Polo Shirt')
                    @slot('name', 'Kaus Polo Scuderia Ferrari 2025 Team Pria')
                    @slot('price', 'Rp 1.450.000')
                @endcomponent
                
            </div>
        </section>
    </main>

    @include('components.footer')

@endsection