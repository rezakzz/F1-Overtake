<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - F1 Store</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styleAdmin.css') }}">

  <style>
    body {
      background-color: var(--primary-bg);
      color: var(--text-primary);
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <header class="border-bottom border-secondary py-3 sticky-top" style="background-color:var(--secondary-bg);">
    <div class="container d-flex justify-content-between align-items-center">
      <a href="{{ route('home') }}" class="logo text-decoration-none">F1<span>STORE</span> Admin</a>
      <nav>
        <ul class="nav">
          <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link text-light fw-bold">Dashboard</a></li>
          <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link text-secondary">Pesanan</a></li>
          <li class="nav-item"><a href="{{ route('admin.produk.index') }}" class="nav-link text-secondary">Produk</a></li>
          <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'text-light fw-bold' : 'text-secondary' }}">User & Role</a></li>
        </ul>
      </nav>
    </div>
  </header>

  @php
    $rp = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
  @endphp

  <main class="container py-5">
    <h2 class="section-title mb-5 text-center text-uppercase">Dashboard Penjualan</h2>

    {{-- METRICS (seperti HTML kamu) --}}
    <section class="row g-4 text-center">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="p-4 rounded-3" style="background-color:var(--secondary-bg);">
          <h5>Total Penjualan</h5>
          <p class="fw-bold fs-2 text-danger mb-0">{{ $rp($totalSales ?? 0) }}</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="p-4 rounded-3" style="background-color:var(--secondary-bg);">
          <h5>Jumlah Pelanggan</h5>
          <p class="fw-bold fs-2 text-danger mb-0">{{ $totalCustomers ?? 0 }}</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="p-4 rounded-3" style="background-color:var(--secondary-bg);">
          <h5>Total Pesanan</h5>
          <p class="fw-bold fs-2 text-danger mb-0">{{ $totalOrders ?? 0 }}</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="p-4 rounded-3" style="background-color:var(--secondary-bg);">
          <h5>Pesanan Pending</h5>
          <p class="fw-bold fs-2 text-danger mb-0">{{ $pendingOrders ?? 0 }}</p>
        </div>
      </div>
    </section>
    </section>

    {{-- Produk Terlaris --}}
    <section class="mt-5">
      <h3 class="section-title mb-4 text-center">Produk Terlaris</h3>
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle rounded-3 overflow-hidden">
          <thead style="background-color:#1f1f23;">
            <tr>
              <th>Produk</th>
              <th>Kategori</th>
              <th>Terjual</th>
              <th>Pendapatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($topProducts ?? []) as $p)
              <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category }}</td>
                <td>{{ (int)$p->sold }}</td>
                <td>{{ $rp($p->revenue ?? 0) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-secondary py-4">Belum ada data.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    {{-- Laporan Stok --}}
    <section class="mt-5">
      <h3 class="section-title mb-4 text-center">Laporan Stok</h3>
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle rounded-3 overflow-hidden">
          <thead style="background-color:#1f1f23;">
            <tr>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Stok Tersedia</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse(($stockReport ?? []) as $p)
              @php
                $st = (int)($p->stock ?? 0);
                $status = $p->status ?? ($st <= 0 ? 'Habis' : ($st <= 5 ? 'Menipis' : 'Aman'));
                $badge = $status === 'Habis' ? 'bg-danger' : ($status === 'Menipis' ? 'bg-warning text-dark' : 'bg-success');
              @endphp
              <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category }}</td>
                <td>{{ $st }}</td>
                <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-secondary py-4">Belum ada data.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    {{-- Grafik Performa Penjualan --}}
    <section class="mt-5">
      <h3 class="section-title mb-4 text-center">Grafik Performa Penjualan</h3>
      <div class="card p-4" style="background-color:var(--secondary-bg);">
        <canvas id="salesChart" height="120"></canvas>
      </div>
    </section>
  </main>

  <footer class="border-top border-secondary text-center py-4">
    <p class="text-secondary mb-0">&copy; 2025 F1 Grand Store Dashboard. Dibuat oleh Kelompok 7.</p>
  </footer>

  {{-- Chart: pakai data dari controller (bulan lalu/bulan ini/hari ini) --}}
  <script>
    console.log('labels:', @json($labels ?? []));
    console.log('data:', @json($data ?? []));
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
          labels: @json($labels ?? []),
            datasets: [{
                label: 'Penjualan',
                data: @json($data ?? []),
                borderWidth: 3,
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
  </script>

  {{-- HAPUS ini karena sudah tidak pakai API frontend --}}
  {{--
  <script src="../../js/api.js"></script>
  <script src="../../js/admin-dashboard.js"></script>
  --}}
</body>
</html>
