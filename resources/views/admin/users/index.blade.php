<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User & Role - Admin</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styleAdmin.css') }}">
  <style>
    :root{
      --primary-bg:#0b0b0f;
      --secondary-bg:#15151c;
      --border:#2a2a35;
      --text:#e9e9ef;
      --muted:#9aa0aa;
      --red:#e10600;
    }

    .section-title{
      text-align:center;
      font-weight:900;
      text-transform:uppercase;
      letter-spacing:1px;
      margin:28px 0 18px;
    }

    .panel{
      background:var(--secondary-bg);
      border:1px solid var(--border);
      border-radius:14px;
      overflow:hidden;
      box-shadow:0 10px 30px rgba(0,0,0,.35);
    }
    .panel-header{
      padding:12px 16px;
      border-bottom:1px solid var(--border);
      background:rgba(255,255,255,.03);
      font-weight:800;
      text-align:center;
    }
    .panel-body{ padding:16px; }

    .form-control, .form-select{
      background:#111118;
      border:1px solid var(--border);
      color:#fff;
    }
    .form-control:focus, .form-select:focus{
      box-shadow:none;
      border-color:#3a3a4a;
    }

    .btn-red{
      background:var(--red);
      border:none;
      color:#fff;
      font-weight:800;
      padding:10px 18px;
      border-radius:8px;
    }
    .btn-red:hover{ filter:brightness(.95); }

    .table thead th{
      background:#111118 !important;
      border-color:var(--border) !important;
      color:#fff !important;
      font-weight:800;
    }
    .table td, .table th{ border-color:var(--border) !important; vertical-align:middle; }
    .role-admin{ color:var(--red); font-weight:900; }

    .btn-outline{
      border:1px solid #777;
      background:transparent;
      color:#fff;
      font-weight:800;
      border-radius:8px;
      padding:8px 14px;
      min-width:86px;
    }
    .btn-outline:hover{ background:rgba(255,255,255,.05); }

    .btn-disabled{
      background:#666 !important;
      border:1px solid #666 !important;
      color:#222 !important;
      cursor:not-allowed;
      opacity:.8;
    }

    .hint-box{
      background:rgba(255,255,255,.03);
      border:1px solid var(--border);
      border-radius:14px;
      padding:18px;
      margin-top:18px;
    }
    .hint-box b{ color:#fff; }
    .hint-box p{ margin:6px 0; color:#cfd3da; }
  </style>
</head>

<body>
<header class="border-bottom border-secondary py-3 sticky-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="logo text-decoration-none" href="#">F1<span>STORE</span> Admin</a>

    <nav>
      <ul class="nav">
        <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('admin.orders.index') }}">Pesanan</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('admin.produk.index') }}">Produk</a></li>
        <li class="nav-item"><a class="nav-link text-light fw-bold" href="{{ route('admin.users.index') }}">User & Role</a></li>
      </ul>
    </nav>
  </div>
</header>

<main class="container py-4">
  <h2 class="section-title">Manajemen User & Role</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- Tambah user --}}
  <div class="panel mb-4">
    <div class="panel-header">Tambah User Baru</div>
    <div class="panel-body">
      <form method="POST" action="{{ route('admin.users.store') }}" class="row g-2 align-items-center">
        @csrf
        <div class="col-md-3">
          <input class="form-control" name="name" placeholder="Nama" required>
        </div>
        <div class="col-md-3">
          <input class="form-control" name="email" placeholder="Email" type="email" required>
        </div>
        <div class="col-md-3">
          <select class="form-select" name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="superadmin">Super Admin</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
            <option value="viewer">Viewer</option>
            <option value="customer">Customer</option>
          </select>
        </div>
        <div class="col-md-3">
          <input class="form-control" name="password" placeholder="Password" type="password" required>
        </div>

        <div class="col-12 d-flex justify-content-center mt-2">
          <button class="btn-red">TAMBAH USER</button>
        </div>
      </form>
    </div>
  </div>

  <h3 class="text-center fw-bold mb-3" style="font-size:2rem;">Daftar User & Role</h3>

  <div class="panel">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Hak Akses</th>
            <th style="width:220px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $u)
            @php
              $access = match($u->role){
                'superadmin' => 'Semua Akses (Full Control)',
                'admin' => 'Kelola produk, stok, pesanan',
                'staff' => 'Kelola stok dan data dasar',
                'viewer' => 'Hanya melihat laporan & statistik',
                default => 'Akses Pelanggan'
              };
            @endphp

            <tr>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td class="{{ in_array($u->role,['admin','superadmin']) ? 'role-admin' : '' }}">
                {{ ucfirst($u->role) }}
              </td>
              <td>{{ $access }}</td>

              <td class="d-flex gap-2">
                {{-- Edit role --}}
                @if(in_array($u->role,['admin','superadmin']) && $u->id !== auth()->id())
                  <button class="btn-outline btn-disabled" type="button">ADMIN</button>
                @else
                  <form method="POST" action="{{ route('admin.users.role', $u->id) }}" class="d-flex gap-2">
                    @csrf
                    @method('PUT')
                    <select name="role" class="form-select form-select-sm" style="width:auto;">
                      <option value="superadmin" @selected($u->role==='superadmin')>SuperAdmin</option>
                      <option value="admin" @selected($u->role==='admin')>Admin</option>
                      <option value="staff" @selected($u->role==='staff')>Staff</option>
                      <option value="viewer" @selected($u->role==='viewer')>Viewer</option>
                      <option value="customer" @selected($u->role==='customer')>Customer</option>
                    </select>
                    <button class="btn-outline" type="submit">EDIT</button>
                  </form>
                @endif

                {{-- Hapus --}}
                @if($u->id !== auth()->id())
                  <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}"
                        onsubmit="return confirm('Hapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn-red" type="submit" style="padding:8px 14px;">HAPUS</button>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <h3 class="text-center fw-bold mt-5" style="font-size:2rem;">Hak Akses Per Role</h3>

  <div class="hint-box">
    <p><b>Super Admin:</b> Semua akses (kelola user, produk, pesanan, laporan)</p>
    <p><b>Admin:</b> Kelola produk, stok, pesanan</p>
    <p><b>Staff:</b> Kelola stok dan data dasar</p>
    <p><b>Viewer:</b> Hanya melihat laporan & statistik</p>
  </div>
</main>

<footer class="border-top border-secondary text-center py-4 mt-5">
  <p class="text-secondary mb-0">&copy; 2025 F1 Grand Store Dashboard. Dikelola oleh Super Admin.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
