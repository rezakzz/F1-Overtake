@extends('layouts.front')
@section('title', 'Home Page')
@section('content')
@include('components.navbar')

    <div class="container py-5">
        <h3 class="mb-4">Keranjang Belanja</h3>

        @php
            $rp = fn($n) => 'Rp '.number_format($n,0,',','.');
        @endphp

        @if($cartItems->isEmpty())
            <div class="alert alert-warning">Keranjang masih kosong</div>
        @else
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $rp($item->product->price) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $rp($item->quantity * $item->product->price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Total: <span class="text-danger">{{ $rp($total) }}</span></h4>

                {{-- TOMBOL CHECKOUT --}}
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>
        @endif
    </div>
    @include('components.footer')
        
@endsection
