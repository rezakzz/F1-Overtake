<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Produk</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/styleAdmin.css') }}">

  <style>
    :root{
      --primary-bg:#0b0b0f;
      --secondary-bg:#15151c;
      --card-bg:#1c1c24;
      --border:#2a2a35;
      --text:#e9e9ef;
      --muted:#9aa0aa;
      --red:#e10600;
      --cyan:#1fb6d5;
    }

    .section-title{
      letter-spacing:1px;
      font-weight:900;
      text-transform:uppercase;
      text-align:center;
      margin:30px 0 25px;
    }

    .stat-card{
      background:linear-gradient(135deg, var(--card-bg), #111118);
      border:1px solid var(--border);
      border-radius:14px;
      padding:18px;
      text-align:center;
      box-shadow:0 10px 30px rgba(0,0,0,.35);
    }
    .stat-card .num{ font-size:2rem; font-weight:900; color:var(--red); margin:0; }
    .stat-card .label{ color:var(--muted); margin:0; }

    .panel{
      background:var(--secondary-bg);
      border:1px solid var(--border);
      border-radius:14px;
      overflow:hidden;
      box-shadow:0 10px 30px rgba(0,0,0,.35);
    }
    .panel-header{
      background:rgba(255,255,255,.03);
      border-bottom:1px solid var(--border);
      padding:12px 16px;
      display:flex;
      align-items:center;
      gap:10px;
      font-weight:800;
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
    .btn-danger{ background:var(--red); border:none; }
    .btn-danger:hover{ filter:brightness(.95); }

    .table-wrap{
      background:transparent;
      border-top:1px solid var(--border);
    }
    table{ margin:0; }
    .table thead th{
      background:#111118 !important;
      color:#fff !important;
      border-color:var(--border) !important;
      font-weight:800;
    }
    .table td, .table th{ border-color:var(--border) !important; vertical-align:middle; }
    .product-cell{ display:flex; align-items:center; gap:12px; }
    .thumb{
      width:44px; height:44px; border-radius:10px;
      background:#fff; object-fit:cover;
      border:1px solid #ddd;
    }

    .action-stack{
      display:flex;
      flex-direction:column;
      gap:8px;
      align-items:flex-end;
    }
    .icon-btn{
      width:44px; height:44px;
      display:flex; align-items:center; justify-content:center;
      border-radius:10px;
      border:1px solid rgba(31,182,213,.4);
      background:transparent;
      color:var(--cyan);
    }
    .icon-btn.delete{
      border-color:rgba(225,6,0,.35);
      color:var(--red);
    }
    .icon-btn:hover{ background:rgba(255,255,255,.04); }

    .filter-row{
      display:flex; flex-wrap:wrap; gap:10px;
      padding:12px 16px;
      align-items:center;
      justify-content:flex-end;
      border-bottom:1px solid var(--border);
      background:rgba(255,255,255,.02);
    }
    .searchbox{
      display:flex; align-items:center;
      border:1px solid var(--border);
      border-radius:10px;
      overflow:hidden;
      background:#111118;
    }
    .searchbox input{
      border:none; outline:none;
      background:transparent;
      color:#fff;
      padding:8px 10px;
      width:210px;
    }
    .searchbox button{
      border:none;
      background:transparent;
      color:#fff;
      padding:8px 10px;
    }

    .stock-green{ color:#39d98a; font-weight:800; }
    .stock-red{ color:#ff4d4d; font-weight:800; }

    .pagination .page-link{
      background:#111118;
      border-color:var(--border);
      color:#fff;
    }
    .pagination .page-item.active .page-link{
      background:var(--red);
      border-color:var(--red);
    }
  </style>
</head>

<body>
<header class="border-bottom border-secondary py-3 sticky-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="#" class="logo text-decoration-none">F1<span>STORE</span> Admin</a>
    <nav>
      <ul class="nav">
        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link text-secondary">Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link text-secondary">Pesanan</a></li>
        <li class="nav-item"><a href="{{ route('admin.produk.index') }}" class="nav-link text-light fw-bold">Produk</a></li>
        <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link text-secondary">User & Role</a></li>
      </ul>
    </nav>
  </div>
</header>

<main class="container py-4">
  <h2 class="section-title">Manajemen Produk</h2>

  {{-- Stat cards --}}
  <section class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="stat-card">
        <p class="num">{{ $totalProduk }}</p>
        <p class="label">Total Produk</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <p class="num">{{ $stokRendah }}</p>
        <p class="label">Stok Rendah (&lt;10)</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <p class="num">{{ $stokHabis }}</p>
        <p class="label">Stok Habis</p>
      </div>
    </div>
  </section>

  <div class="row g-4">
    {{-- Left: Add product --}}
    <div class="col-lg-4">
      <div class="panel">
        <div class="panel-header">
          <i class="bi bi-plus-circle"></i> Tambah Produk Baru
        </div>
        <div class="panel-body">
          <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nama Produk *</label>
              <input type="text" name="name" class="form-control" placeholder="Masukkan nama produk" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Tim F1 *</label>
              <select name="team_slug" class="form-control" required>
                <option value="">-- Pilih Tim --</option>
                @foreach($teams as $slug => $name)
                  <option value="{{ $slug }}">{{ $name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Kategori *</label>
              <select name="category" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $c)
                  <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
              </select>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="price" class="form-control" value="0" min="0" required>
              </div>
              <div class="col-6">
                <label class="form-label">Stok *</label>
                <input type="number" name="stock" class="form-control" value="0" min="0" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="description" class="form-control" rows="4" placeholder="Deskripsi produk..."></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Gambar Produk *</label>
              <input type="file" name="cover" class="form-control" required>
            </div>

            <button class="btn btn-danger w-100 py-2 fw-bold">Simpan Produk</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Right: Product table --}}
    <div class="col-lg-8">
      <div class="panel">
        <div class="panel-header">
          <i class="bi bi-box-seam"></i> Daftar Produk
        </div>

        <form class="filter-row" method="GET" action="{{ route('admin.produk.index') }}">
          <select name="category" class="form-select" style="width:auto;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $c)
              <option value="{{ $c }}" @selected(request('category')==$c)>{{ $c }}</option>
            @endforeach
          </select>

          <select name="team_slug" class="form-select" style="width:auto;">
            <option value="">Semua Tim</option>
            @foreach($teams as $slug => $label)
              <option value="{{ $slug }}" @selected(request('team_slug')==$slug)>
                {{ $label }}
              </option>
            @endforeach
          </select>

          <div class="searchbox">
            <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
            <button type="submit"><i class="bi bi-search"></i></button>
          </div>
        </form>

        <div class="table-responsive table-wrap">
          <table class="table table-dark table-hover align-middle">
            <thead>
              <tr>
                <th style="width:90px;">Gambar</th>
                <th>Produk</th>
                <th>Tim</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th style="width:110px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($produks as $p)
                <tr>
                  <td>
                    <img class="thumb" src="{{ asset($p->cover) }}" alt="cover">
                  </td>
                  <td class="fw-bold">{{ $p->name }}</td>
                  <td>{{ $p->team_slug ?? '-' }}</td>
                  <td>{{ $p->category }}</td>
                  <td>Rp {{ number_format($p->price,0,',','.') }}</td>
                  <td class="{{ $p->stock<=0 ? 'stock-red' : 'stock-green' }}">
                    {{ $p->stock }}
                  </td>
                  <td>
                    <div class="action-stack">
                      <button type="button" class="icon-btn" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}">
                        <i class="bi bi-pencil"></i>
                      </button>

                      <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn delete">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>

                {{-- Edit modal --}}
                <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content" style="background:var(--secondary-bg); border:1px solid var(--border); color:#fff;">
                      <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold">Edit Produk</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <form action="{{ route('admin.produk.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          @method('PUT')

                          <div class="row g-2">
                            <div class="col-md-8">
                              <label class="form-label">Nama Produk</label>
                              <input name="name" class="form-control" value="{{ $p->name }}" required>
                            </div>
                            <div class="col-md-4">
                              <label class="form-label">Stok</label>
                              <input type="number" name="stock" class="form-control" value="{{ $p->stock }}" min="0" required>
                            </div>
                          </div>

                          <div class="row g-2 mt-2">
                            <div class="col-md-6">
                              <label class="form-label">Kategori</label>
                              <input name="category" class="form-control" value="{{ $p->category }}" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Tim</label>
                              <input name="team_slug" class="form-control" value="{{ $p->team_slug }}">
                            </div>
                          </div>

                          <div class="row g-2 mt-2">
                            <div class="col-md-6">
                              <label class="form-label">Harga</label>
                              <input type="number" name="price" class="form-control" value="{{ $p->price }}" min="0" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label">Ganti Gambar (opsional)</label>
                              <input type="file" name="cover" class="form-control">
                            </div>
                          </div>

                          <div class="mt-2">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ $p->description }}</textarea>
                          </div>

                          <button class="btn btn-danger w-100 mt-3 fw-bold">Simpan Perubahan</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-secondary py-4">Belum ada produk</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="p-3">
          {{ $produks->links() }}
        </div>
      </div>
    </div>
  </div>
</main>

<footer class="border-top border-secondary text-center py-4 mt-5">
  <p class="text-secondary mb-0">&copy; 2025 F1 Store Admin</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
