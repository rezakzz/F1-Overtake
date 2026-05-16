@extends('layouts.front')
@section('title','Checkout')

@section('content')
@include('components.navbar')
  <style>
    .cardx { background:#121826; border:1px solid rgba(255,255,255,.08); border-radius:14px; }
    .muted { color:#98a2b3; }
  </style>
  @php
    $rp = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
    $badge = match($order->status){
      'pending' => 'bg-warning text-dark',
      'approved' => 'bg-primary',
      'processing' => 'bg-info text-dark',
      'shipped' => 'bg-secondary',
      'delivered' => 'bg-success',
      'cancelled' => 'bg-danger',
      default => 'bg-secondary'
    };
  @endphp

  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold mb-1">Detail Pesanan #{{ $order->id }}</h3>
        <div class="muted">{{ $order->created_at->format('d M Y, H:i') }}</div>
      </div>
      <a href="{{ route('orders.my') }}" class="btn btn-outline-light btn-sm">Kembali</a>
    </div>

    <div class="row g-3">
      <div class="col-12 col-lg-4">
        <div class="cardx p-3">
          <p class="muted small">Status Pembayaran</p>
          @if ($order->payment_status === 'paid')
              <span class="fw-semibold">LUNAS</span>
          @elseif ($order->payment_status === 'pending')
              <span class="fw-semibold">MENUNGGU PEMBAYARAN</span>
          @elseif ($order->payment_status === 'expired')
              <span class="fw-semibold">KADALUARSA</span>
          @else
              <span class="fw-semibold">GAGAL</span>
          @endif
          <div class="muted small mt-2">Status Pengiriman</div>
          <div class="mt-1">
            <span class="badge {{ $badge }}">{{ strtoupper($order->status) }}</span>
          </div>

          <hr style="border-color:rgba(255,255,255,.08)">

          <div class="muted small">Nama</div>
          <div class="fw-semibold">{{ $order->customer_name ?? '-' }}</div>

          <div class="muted small mt-3">Email</div>
          <div class="fw-semibold">{{ $order->customer_email ?? '-' }}</div>

          <hr style="border-color:rgba(255,255,255,.08)">

          <div class="d-flex justify-content-between">
            <span class="muted">Total</span>
            <span class="fw-bold">{{ $rp($order->total) }}</span>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-8">
        <div class="cardx p-3">
          <h5 class="fw-bold mb-3">Item Pesanan</h5>

          <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
              <thead style="background:rgba(255,255,255,.04)">
                <tr class="muted">
                  <th>Produk</th>
                  <th class="text-end">Harga</th>
                  <th class="text-end">Qty</th>
                  <th class="text-end">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $it)
                  <tr>
                    <td class="fw-semibold">{{ $it->product->name ?? '-' }}</td>
                    <td class="text-end">{{ $rp($it->product->price) }}</td>
                    <td class="text-end">{{ $it->qty }}</td>
                    <td class="text-end fw-semibold">{{ $rp($it->subtotal) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

    @if ($order->canPay())
      <a href="{{ route('pay', $order->id) }}"
        class="btn btn-primary mt-4">
          Bayar Sekarang
      </a>
    @endif
  </div>
  @include('components.footer')
@endsection
