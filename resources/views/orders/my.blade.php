@extends('layouts.front')
@section('title','Pesanan Saya')

@section('content')
@include('components.navbar')

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold mb-1">Pesanan Saya</h3>
      <div class="muted">Riwayat pesanan yang sudah checkout</div>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">Kembali</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @php
    $rp = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
  @endphp

  <div class="cardx p-3">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead style="background:rgba(255,255,255,.04)">
          <tr class="muted">
            <th style="width:90px;">ID</th>
            <th style="width:200px;">Tanggal</th>
            <th style="width:170px;">Total</th>
            <th style="width:140px;">Status Pembayaran</th>
            <th style="width:140px;">Status Pengiriman</th>
            <th class="text-end" style="width:140px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $o)
            @php
              $badge = match($o->status){
                'pending' => 'bg-warning text-dark',
                'payed' => 'bg-success',
                'approved' => 'bg-primary',
                'processing' => 'bg-info text-dark',
                'shipped' => 'bg-secondary',
                'delivered' => 'bg-success',
                'cancelled' => 'bg-danger',
                default => 'bg-secondary'
              };
              $badgePay = match($o->payment_status){
                'pending' => 'bg-warning text-dark',
                'paid' => 'bg-success',
                'expired' => 'bg-secondary',
                default => 'bg-danger'
              };
            @endphp
            <tr>
              <td class="fw-semibold">#{{ $o->order_code ?? ('ORD-' . $o->id)}}</td>
              <td>{{ $o->created_at->format('d M Y, H:i') }}</td>
              <td class="fw-semibold">{{ $rp($o->total) }}</td>
              <td><span class="badge {{ $badgePay }}">{{ strtoupper($o->payment_status) }}</span></td>
              <td><span class="badge {{ $badge }}">{{ strtoupper($o->status) }}</span></td>
              <td class="text-end">
                <a href="{{ route('orders.my.detail', $o->id) }}" class="btn btn-outline-light btn-sm">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center muted py-4">Belum ada pesanan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="d-flex justify-content-end mt-4">
    {{ $orders->links('pagination::bootstrap-5') }}
  </div>
</div>

@include('components.footer')
@endsection
