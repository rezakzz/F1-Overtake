<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Manajemen Pesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styleAdmin.css') }}">

  {{-- STYLE asli (punyamu) --}}
  <style>
    body {
      background-color: var(--primary-bg) !important;
      color: var(--text-primary) !important;
    }

    header, footer {
      background-color: var(--secondary-bg) !important;
    }

    .card, .table, .form-select, .form-control {
      background-color: var(--secondary-bg) !important;
      color: var(--text-primary) !important;
    }

    .table thead {
      background-color: #1f1f23 !important;
      color: #fff !important;
    }

    .btn-danger {
      background-color: var(--primary-red) !important;
      border: none;
    }

    .section-title {
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 700;
    }

    .stat-card {
      background: linear-gradient(135deg, var(--secondary-bg) 0%, #2a2a2e 100%);
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      border: 1px solid var(--border-color);
    }

    .stat-card h3 {
      font-size: 2rem;
      margin-bottom: 5px;
    }

    .stat-card small {
      color: #ccc !important;
    }

    .stat-card.pending h3 { color: #ffc107; }
    .stat-card.processing h3 { color: #17a2b8; }
    .stat-card.completed h3 { color: #28a745; }

    .order-detail-card {
      position: sticky;
      top: 80px;
    }

    #orderTableBody tr {
      transition: background-color 0.2s;
    }

    #orderTableBody tr:hover {
      background-color: rgba(255, 24, 1, 0.1) !important;
    }

    #orderTableBody tr.table-active {
      background-color: rgba(255, 24, 1, 0.2) !important;
    }

    .badge {
      font-size: 0.8rem;
      padding: 5px 10px;
    }

    /* tombol detail biar mirip contoh */
    .btn-detail {
      background: #1aa7c8;
      color: #0b1116;
      font-weight: 800;
      border: 0;
      border-radius: 10px;
      padding: 8px 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-detail:hover { opacity: .9; }
  </style>
</head>

<body>

  {{-- NAVBAR ADMIN (samakan dengan halaman lain kamu) --}}
  <header class="border-bottom border-secondary py-3 sticky-top" style="background-color:var(--secondary-bg);">
    <div class="container d-flex justify-content-between align-items-center">
      <a href="{{ route('home') }}" class="logo text-decoration-none">
        F1<span>STORE</span> Admin
      </a>
  
      <nav>
        <ul class="nav">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'text-light fw-bold' : 'text-secondary' }}">
              Dashboard
            </a>
          </li>
  
          <li class="nav-item">
            <a href="{{ route('admin.orders.index') }}"
               class="nav-link {{ request()->routeIs('admin.orders.*') ? 'text-light fw-bold' : 'text-secondary' }}">
              Pesanan
            </a>
          </li>
  
          <li class="nav-item">
            <a href="{{ route('admin.produk.index') }}"
               class="nav-link {{ request()->routeIs('admin.produk.*') ? 'text-light fw-bold' : 'text-secondary' }}">
              Produk
            </a>
          </li>
  
          <li class="nav-item">
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'text-light fw-bold' : 'text-secondary' }}">
              User & Role
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container py-5">
    <h2 class="section-title text-center mb-5">Manajemen Pesanan</h2>

    {{-- STAT CARDS --}}
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="stat-card pending">
          <h3 id="pendingCount">{{ $pendingCount }}</h3>
          <small>Menunggu Persetujuan</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat-card processing">
          <h3 id="processCount">{{ $processCount }}</h3>
          <small>Dalam Proses</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat-card completed">
          <h3 id="doneCount">{{ $completedCount }}</h3>
          <small>Selesai</small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      {{-- LEFT: TABLE --}}
      <div class="col-lg-8">
        <div class="card p-0 rounded-4 shadow-sm">
          <div class="d-flex justify-content-between align-items-center p-3 border-bottom border-secondary">
            <h5 class="mb-0 fw-bold">Daftar Pesanan</h5>

            <div class="d-flex gap-2 align-items-center">
              <select id="statusFilter" class="form-select form-select-sm" style="max-width: 160px;">
                <option value="">Semua Pesanan</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>

              <div class="input-group input-group-sm" style="max-width: 240px;">
                <input id="searchInput" type="text" class="form-control" placeholder="Cari order...">
                <button class="btn btn-outline-light" type="button" onclick="applyFilter()">🔍</button>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th>ID Pesanan</th>
                  <th>Pelanggan</th>
                  <th>Tanggal</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>

              <tbody id="orderTableBody">
                @forelse($orders as $o)
                  <tr
                    data-id="{{ $o['id'] }}"
                    data-status="{{ $o['status'] ?? '' }}"
                    data-search="{{ strtolower(($o['code'] ?? '') . ' ' . ($o['customer_name'] ?? '') . ' ' . ($o['customer_email'] ?? '')) }}"
                    data-json='@json($o)'
                  >
                    <td class="fw-bold">#{{ $o['code'] ?? $o['id'] }}</td>
                    <td>{{ $o['customer_name'] ?? 'Customer' }}</td>
                    <td>{{ $o['date'] ?? '-' }}</td>
                    <td class="fw-bold">{{ $o['total'] ?? 'Rp 0' }}</td>

                    {{-- penting: class status-cell agar bisa di-update setelah PATCH --}}
                    <td class="status-cell">
                      {!! $o['status_badge_html'] ?? '<span class="badge bg-warning text-dark">Pending</span>' !!}
                    </td>

                    <td>
                      <button type="button" class="btn-detail" onclick="showDetail(this)">
                        👁 <span>DETAIL</span>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Belum ada pesanan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div>
      </div>

      {{-- RIGHT: DETAIL --}}
      <div class="col-lg-4">
        <div class="card rounded-4 shadow-sm order-detail-card p-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Detail Pesanan</h5>
          </div>

          <hr class="border-secondary">

          <div id="detailBox" class="text-secondary">
            Pilih pesanan untuk melihat detail.
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="border-top border-secondary text-center py-4 mt-5">
    <p class="text-secondary mb-0">&copy; 2025 F1 Store Admin</p>
  </footer>

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const updateUrlBase = "{{ url('/admin/orders') }}"; // + /{id}/status

    function statusBadge(status){
      switch((status || '').toLowerCase()){
        case 'approved':   return '<span class="badge bg-primary">Approved</span>';
        case 'processing': return '<span class="badge bg-info text-dark">Processing</span>';
        case 'shipped':    return '<span class="badge bg-secondary">Shipped</span>';
        case 'delivered':  return '<span class="badge bg-success">Delivered</span>';
        case 'cancelled':  return '<span class="badge bg-danger">Cancelled</span>';
        default:           return '<span class="badge bg-warning text-dark">Pending</span>';
      }
    }
    function paymentBadge(pay){
      switch((pay || '').toLowerCase()){
        case 'paid':    return '<span class="badge bg-success">Paid</span>';
        case 'pending': return '<span class="badge bg-warning text-dark">Pending</span>';
        case 'expired': return '<span class="badge bg-secondary">Expired</span>';
        case 'failed':  return '<span class="badge bg-danger">Failed</span>';
        default:        return '<span class="badge bg-secondary">-</span>';
      }
    }

    async function updateOrderStatus(orderId, newStatus, tr){
    const res = await fetch(`${updateUrlBase}/${orderId}/status`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({ status: newStatus })
    });

    if (!res.ok) {
      const msg = await res.text();
      alert("Gagal update status.\n" + msg);
      return false;
    }

    const data = await res.json();

    // ✅ update dataset + badge pakai status dari server
    tr.dataset.status = data.status;

    const cell = tr.querySelector('.status-cell');
    if (cell) cell.innerHTML = statusBadge(data.status);

    // update object di dataset.json biar detail konsisten
    const obj = JSON.parse(tr.dataset.json);
    obj.status = data.status;
    obj.status_badge_html = statusBadge(data.status);
    tr.dataset.json = JSON.stringify(obj);

    return true;
  }

    function showDetail(btn){
      const tr = btn.closest('tr');
      const data = JSON.parse(tr.dataset.json);

      document.querySelectorAll('#orderTableBody tr').forEach(r => r.classList.remove('table-active'));
      tr.classList.add('table-active');

      const items = Array.isArray(data.items) ? data.items : [];
      const itemsRows = items.map(it => `
        <tr>
          <td>${it.name ?? '-'}</td>
          <td>${it.qty ?? 0}</td>
          <td>${it.price ?? '-'}</td>
          <td>${it.subtotal ?? '-'}</td>
        </tr>
      `).join('');

      const currentStatus = (data.status || 'pending').toLowerCase();
      const payStatus = (data.payment_status || 'pending').toLowerCase();

      document.getElementById('detailBox').classList.remove('text-secondary');
      document.getElementById('detailBox').innerHTML = `

        <div class="mb-2">
          <div><b>Penerima:</b> ${data.recipient_name} (${data.phone})</div>
          <div><b>Alamat:</b> ${data.address}</div>
          ${data.note ? `<div><b>Catatan:</b> ${data.note}</div>` : ``}
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="fw-bold">#${data.code ?? data.id}</div>
          <div id="detailBadge">${statusBadge(currentStatus)}</div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="fw-bold">Pembayaran</div>
          <div id="paymentBadge">${paymentBadge(payStatus)}</div>
        </div>
        <div class="d-flex gap-2 align-items-center my-3">
          <label class="fw-bold">Status:</label>
          <select id="statusSelect" class="form-select form-select-sm" style="max-width: 200px;">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <button id="saveStatusBtn" class="btn btn-outline-light btn-sm">Simpan</button>
        </div>

        <hr class="border-secondary">

        <div class="fw-bold mb-2">Item Pesanan</div>

        <div class="table-responsive">
          <table class="table table-dark table-sm align-middle mb-0">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${itemsRows || `<tr><td colspan="4" class="text-secondary">Belum ada item</td></tr>`}
            </tbody>
          </table>
        </div>

        <hr class="border-secondary">

        <div class="d-flex justify-content-between fw-bold mt-2">
          <div>Total:</div>
          <div class="text-danger">${data.total ?? 'Rp 0'}</div>
        </div>
      `;

      document.getElementById('saveStatusBtn').onclick = async () => {
        const newStatus = document.getElementById('statusSelect').value.toLowerCase();
        const oldStatus = String(tr.dataset.status || '').toLowerCase();

        tr.dataset.status = newStatus;
        const cell = tr.querySelector('.status-cell');
        if (cell) cell.innerHTML = statusBadge(newStatus);
        document.getElementById('detailBadge').innerHTML = statusBadge(newStatus);

        recalcCardsFromTable();
        applyFilter();

        const btn = document.getElementById('saveStatusBtn');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';

        const ok = await updateOrderStatus(data.id, newStatus, tr);


        if (!ok) {
          tr.dataset.status = oldStatus;
          if (cell) cell.innerHTML = statusBadge(oldStatus);
          document.getElementById('detailBadge').innerHTML = statusBadge(oldStatus);

          document.getElementById('statusSelect').value = oldStatus;

          recalcCardsFromTable();
          applyFilter();
        }

        btn.disabled = false;
        btn.textContent = 'Simpan';
      };
    }

    function applyFilter(){
      const status = (document.getElementById('statusFilter')?.value || '').toLowerCase();
      const q = (document.getElementById('searchInput')?.value || '').toLowerCase();

      document.querySelectorAll('#orderTableBody tr').forEach(tr => {
        const rowStatus = (tr.dataset.status || '').toLowerCase();
        const rowSearch = (tr.dataset.search || '').toLowerCase();

        const okStatus = !status || rowStatus === status;
        const okSearch = !q || rowSearch.includes(q);

        tr.style.display = (okStatus && okSearch) ? '' : 'none';
      });
    }

    function recalcCardsFromTable(){
    let pending = 0;
    let process = 0;
    let done = 0;

    document.querySelectorAll('#orderTableBody tr').forEach(tr => {
      const st = String(tr.dataset.status || '').toLowerCase().trim();


      if (st === 'pending') pending++;


      else if (st === 'approved' || st === 'processing' || st === 'shipped') process++;


      else if (st === 'delivered') done++;
    });

    const elPending = document.getElementById('pendingCount');
    const elProcess = document.getElementById('processCount');
    const elDone = document.getElementById('doneCount');

    if (elPending) elPending.textContent = pending;
    if (elProcess) elProcess.textContent = process;
    if (elDone) elDone.textContent = done;
  }

    document.getElementById('statusFilter')?.addEventListener('change', applyFilter);
    document.getElementById('searchInput')?.addEventListener('input', applyFilter);
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
