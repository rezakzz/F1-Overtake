<a href="{{ route('product.detail', $id ?? 1) }}">
    <div class="product-card">
        <img src="{{ asset($cover) }}" alt="{{ $name }}" class="product-image">
        <div class="product-info">
            <span class="product-category">{{ $category}}</span>
            <h3>{{$name}}</h3>
            <span class="product-price">{{$price}}</span>
        </div>
    </div>
</a>