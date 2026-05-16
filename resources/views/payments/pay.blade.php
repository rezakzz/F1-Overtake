<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pembayaran</title>

  <script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}">
  </script>

  <style>
    body{font-family:system-ui,Segoe UI,Arial; background:#0f1115; color:#fff; padding:40px}
    .card{max-width:520px; margin:auto; background:#1b1f2a; padding:24px; border-radius:14px}
    .btn{display:inline-block; padding:12px 16px; border-radius:10px; background:#e10600; color:#fff; border:0; cursor:pointer; font-weight:700}
    .muted{color:#b8c0cc}
    .row{display:flex; justify-content:space-between; margin:10px 0}
  </style>
</head>
<body>
  <div class="card">
    <h2>Bayar Pesanan</h2>
    <p class="muted">Selesaikan pembayaran untuk melanjutkan proses pesanan.</p>

    <div class="row">
      <div>Order</div>
      <div><b>#{{ $order->order_code ?? ('ORD-' . $order->id) }}</b></div>
    </div>
    <div class="row">
      <div>Total</div>
      <div><b>Rp {{ number_format((float)$order->total, 0, ',', '.') }}</b></div>
    </div>

    <hr style="border:0;border-top:1px solid #2a3142;margin:16px 0">

    <button id="pay-button" class="btn">Bayar Sekarang</button>
    <a href="{{ url('/my-orders') }}" style="margin-left:12px;color:#b8c0cc">Nanti saja</a>
  </div>

  <script>
    document.getElementById('pay-button').addEventListener('click', function () {
      snap.pay('{{ $order->snap_token }}', {
        onSuccess: function (result) {
          window.location.href = "{{ route('pay.success',$order) }}";
        },
        onPending: function (result) {
          alert("Menunggu pembayaran. Kamu bisa cek status di My Orders.");
          window.location.href = "{{ url('/my-orders') }}";
        },
        onError: function (result) {
          alert("Pembayaran gagal. Coba lagi.");
          console.error(result);
        },
        onClose: function () {
          alert("Kamu menutup popup tanpa menyelesaikan pembayaran.");
        }
      });
    });
  </script>
  
</body>
</html>
