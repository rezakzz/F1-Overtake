@extends('layouts.front')

@section('title', 'Reset Password')

@section('content')
@include('components.navbar')

<div class="container" style="max-width:520px; margin:40px auto;">
    <h2 style="margin-bottom:10px;">Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div style="margin-bottom:12px;">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
            @error('email')
                <div style="color:#ff4d4d; margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:12px;">
            <label>Password Baru</label>
            <input type="password" name="password" required
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
            @error('password')
                <div style="color:#ff4d4d; margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:12px;">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                   style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <button type="submit" style="padding:10px 14px; border-radius:8px; border:none; cursor:pointer;">
            Simpan Password Baru
        </button>
    </form>
</div>
@endsection
