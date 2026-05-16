@extends('layouts.front')

@section('title', $product->name)

@section('content')
@include('components.navbar')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">

<main class="container product-page-container">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Home</a> / 
        <a href="#">{{ $product->category ?? 'Merchandise' }}</a> / 
        
        {{ $product->name }}
    </div>

    <section class="product-detail-section">
        <div class="product-gallery">
            <div class="main-image">
                <img src="{{ asset($product->cover) }}" alt="{{ $product->name }}" id="mainProductImage">
            </div>
            
            <div class="thumbnail-images">
                <img src="{{ asset($product->cover) }}" 
                     alt="View 1" 
                     class="thumbnail-btn active" 
                     data-large-src="{{ asset($product->cover) }}">
                <img src="{{ asset($product->cover) }}" 
                     alt="View 2" 
                     class="thumbnail-btn"
                     data-large-src="{{ asset($product->cover) }}">
            </div>
        </div>
        <div class="product-info-panel">
            <p class="product-category-detail">{{ $product->category ?? 'F1 Official Gear' }}</p>
            <h1 class="product-title">{{ $product->name }}</h1>
            
            <div class="product-rating">
                <span class="stars">★★★★</span>
                <span class="review-count">(120 Reviews)</span>
            </div>

            <p class="product-price-detail">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            
            <div class="product-options">
                <div class="option-group">
                    <label>Pilih Ukuran:</label>
                    <div class="size-selector">
                        <button class="size-btn">S</button>
                        <button class="size-btn active">M</button>
                        <button class="size-btn">L</button>
                        <button class="size-btn">XL</button>
                        <button class="size-btn">XXL</button> 
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button type="button" class="btn btn-primary" onclick="addToCart({{ $product->id }}, 1)">
                    🛒 TAMBAH KE KERANJANG
                </button>
            </div>
            <div class="product-accordion">
                <div class="accordion-item active"> 
                    <div class="accordion-header">
                        Deskripsi
                        <span class="accordion-icon">+</span>
                    </div>
                    <div class="accordion-content">
                        <p>{{ $product->description ?? 'Produk resmi F1 musim 2025. Dibuat dengan bahan berkualitas tinggi untuk kenyamanan maksimal para fans.' }}</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <div class="accordion-header">
                        Pengiriman & Pengembalian
                        <span class="accordion-icon">+</span>
                    </div>
                    <div class="accordion-content">
                        <p>Pengiriman standar 3-5 hari kerja. Pengiriman ekspres 1-2 hari kerja. Pengembalian gratis dalam 30 hari setelah barang diterima.</p>
                    </div>
                </div>

            </div> 
        </div> 
    </section> 
    <section class="related-products">
        <h2 class="section-title">Anda Mungkin Juga Suka</h2>
        
        <div class="product-grid">
            @foreach($relatedProducts as $related)
                <a href="{{ route('product.detail', $related->id) }}">
                    <div class="product-card">
                        <img src="{{ asset($related->cover) }}" alt="{{ $related->name }}" class="product-image">
                        <div class="product-info">
                            <span class="product-category">{{ $related->category ?? 'Merchandise' }}</span>
                            <h3>{{ $related->name }}</h3>
                            <span class="product-price">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

</main>

@include('components.footer')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const accordionHeaders = document.querySelectorAll(".accordion-header");
        accordionHeaders.forEach(header => {
            header.addEventListener("click", function() {
                const accordionItem = this.parentElement;
                const isActive = accordionItem.classList.contains("active");
                document.querySelectorAll('.accordion-item.active').forEach(item => {
                        if (item !== accordionItem) item.classList.remove('active');
                });
                accordionItem.classList.toggle("active", !isActive);
            });
        });
        const mainImage = document.getElementById("mainProductImage");
        const thumbnails = document.querySelectorAll(".thumbnail-btn");

        thumbnails.forEach(thumb => {
            thumb.addEventListener("click", function() {
                document.querySelector('.thumbnail-btn.active').classList.remove('active');
                this.classList.add('active');
                mainImage.src = this.dataset.largeSrc; 
            });
        });
        const sizeButtons = document.querySelectorAll(".size-btn:not(.disabled)");
        sizeButtons.forEach(button => {
            button.addEventListener("click", function() {
                const activeBtn = document.querySelector('.size-btn.active');
                if(activeBtn) activeBtn.classList.remove('active');
                this.classList.add('active');
            });
        });

    });

</script>

@endsection