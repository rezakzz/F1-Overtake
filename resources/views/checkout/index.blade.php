@extends('layouts.front')
@section('title','Checkout')

@section('content')
@include('components.navbar')

<div class="container py-5">
  <h3 class="mb-4">Checkout</h3>

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
          <td>Rp {{ number_format($item->product->price,0,',','.') }}</td>
          <td>{{ $item->quantity }}</td>
          <td>Rp {{ number_format($item->quantity * $item->product->price,0,',','.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="d-flex justify-content-between align-items-center mt-4">

    {{-- tombol KONFIRMASI (baru POST) --}}
    <form action="{{ route('checkout') }}" method="POST" class="mt-4">
      @csrf
    
      <div class="card bg-dark text-light mb-4">
        <div class="card-body">
          <h5 class="mb-3">Alamat Pengiriman</h5>
    
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Penerima</label>
              <input type="text"
                     name="recipient_name"
                     class="form-control @error('recipient_name') is-invalid @enderror"
                     value="{{ old('recipient_name') }}"
                     required>
              @error('recipient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
    
            <div class="col-md-6">
              <label class="form-label">No. HP</label>
              <input type="text"
                     name="phone"
                     class="form-control @error('phone') is-invalid @enderror"
                     value="{{ old('phone') }}"
                     required>
              @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
    
            <div class="col-12">
              <label class="form-label">Alamat Lengkap</label>
              <textarea name="shipping_address"
                        rows="3"
                        class="form-control @error('shipping_address') is-invalid @enderror"
                        required>{{ old('shipping_address') }}</textarea>
              @error('shipping_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
    
            <div class="col-md-8">
              <label class="form-label">Kota</label>
              <input type="text"
                     name="city"
                     class="form-control @error('city') is-invalid @enderror"
                     value="{{ old('city') }}"
                     required>
              @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
    
            <div class="col-md-4">
              <label class="form-label">Kode Pos</label>
              <input type="text"
                     name="postal_code"
                     class="form-control @error('postal_code') is-invalid @enderror"
                     value="{{ old('postal_code') }}"
                     required>
              @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
    
            <div class="col-12">
              <label class="form-label">Catatan (opsional)</label>
              <input type="text"
                     name="note"
                     class="form-control @error('note') is-invalid @enderror"
                     value="{{ old('note') }}">
              @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
      </div>
    
      <div class="d-flex justify-content-between align-items-center">
        <h4>Total: <span class="text-danger">Rp {{ number_format($total,0,',','.') }}</span></h4>
    
        <button type="submit" class="btn btn-danger btn-lg">
          Konfirmasi Checkout
        </button>
      </div>
    </form>
  </div>
</div>

@include('components.footer')
@endsection
