<a href="{{ route('landing.Katalog', $slug) }}">
    <div class="team-card" style="--team-color: {{$color}};">
        <img src="{{$logo}}"
            alt="Logo {{$name}}">
        <h3>{{ $name }}</h3>
    </div>
</a>