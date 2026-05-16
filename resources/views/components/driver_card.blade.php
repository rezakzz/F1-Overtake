<a href="{{ route('landing.Katalog', $slug) }}">
    <div class="product-card">
        <img src="{{$cover}}" alt="Driver F1" class="product-image">
        <div class="product-info">
            <span class="product-category">{{$team}}</span>
            <h1>{{$name}}</h1>
        </div>
    </div>
</a>