@extends('layouts.front')

@section('title', 'Lupa Password')

@section('content')
<div class="forgot-container">
    <div class="forgot-card">

        <h2>Lupa Password</h2>
        <p class="forgot-desc">
            Masukkan email akun kamu. Kami akan kirim link untuk reset password.
        </p>

        {{-- NOTIF SUKSES --}}
        @if (session('status'))
            <div class="notice notice-success" id="notice-success">
                <div class="notice-icon">✓</div>
                <div class="notice-text">
                    Link reset password sudah dikirim.
                    <div class="notice-sub">{{ session('status') }}</div>
                </div>
                <button class="notice-close"
                    onclick="document.getElementById('notice-success').remove()">×</button>
            </div>

            <script>
                setTimeout(() => {
                    const el = document.getElementById('notice-success');
                    if (el) el.remove();
                }, 4000);
            </script>
        @endif

        {{-- NOTIF ERROR --}}
        @if ($errors->any())
            <div class="notice notice-error">
                <div class="notice-icon">!</div>
                <div class="notice-text">
                    Terjadi kesalahan:
                    <div class="notice-sub">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder=""
                value="{{ old('email') }}"
                required
            >

            <button type="submit" class="btn-reset">
                Kirim Link Reset
            </button>
        </form>

        <a href="/" class="back-login">← Kembali ke Login</a>

    </div>
</div>
@endsection
