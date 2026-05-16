<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manajemen Produk - F1 Store Admin</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styleAdmin.css') }}">

  <style>
    body {
      background-color: var(--primary-bg) !important;
      color: var(--text-primary) !important;
    }
    .card, .table, .form-control, .form-select {
      background-color: var(--secondary-bg) !important;
      color: var(--text-primary) !important;
      border-color: rgba(255,255,255,.08) !important;
    }
  </style>
</head>

<body>
  <header class="border-bottom border-secondary py-3">
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

  <main class="container py-5">
    <h2 class="section-title mb-5 text-center text-uppercase">Manajemen Produk</h2>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Form tambah produk --}}
    <section class="mb-5">
      <div class="card p-4 rounded-4 shadow-sm">
        <h3 class="section-title mb-4 text-center">Tambah Produk</h3>

        <form id="productForm" method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data">
          @csrf

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Produk</label>
              <input name="name" type="text" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Kategori</label>
              <input name="category" type="text" class="form-control" value="{{ old('category') }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Harga</label>
              <input name="price" type="number" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Cover (Gambar)</label>
              <input name="cover" type="file" class="form-control" accept="image/*">
            </div>

            <div class="col-md-6">
              <label class="form-label">Team Slug (opsional)</label>
              <input name="team_slug" type="text" class="form-control" value="{{ old('team_slug') }}">
            </div>

            <div class="col-12">
              <label class="form-label">Deskripsi (opsional)</label>
              <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="col-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-outline-light fw-bold px-4">Tambah Produk</button>
            </div>
          </div>
        </form>
      </div>
    </section>

    {{-- Search --}}
    <section class="mb-3">
      <form method="GET" action="{{ route('admin.produk.index') }}" class="d-flex gap-2">
        <input class="form-control" type="text" name="q" placeholder="Cari nama/kategori..." value="{{ $q ?? request('q') }}">
        <button class="btn btn-outline-light" type="submit">Cari</button>
        <a class="btn btn-outline-secondary" href="{{ route('admin.produk.index') }}">Reset</a>
      </form>
    </section>

    {{-- Tabel produk --}}
    <section>
      <h3 class="section-title mb-4 text-center">Daftar Produk</h3>

      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle rounded-3 overflow-hidden" id="productTable">
          <thead style="background-color:#1f1f23;">
            <tr>
              <th>Gambar</th>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody id="productTableBody">
            @forelse ($produks as $p)
              <tr>
                <td>
                  @if ($p->cover)
                    <img src="{{ asset('storage/'.$p->cover) }}" alt="Cover" class="rounded" width="60">
                  @else
                    <div class="text-secondary">—</div>
                  @endif
                </td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category }}</td>
                <td>Rp {{ number_format((int) $p->price, 0, ',', '.') }}</td>
                <td class="text-secondary">—</td>
                <td class="d-flex gap-2">
                  {{-- Edit (modal) --}}
                  <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $p->id }}">
                    Edit
                  </button>

                  {{-- Delete --}}
                  <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm fw-bold" type="submit">Hapus</button>
                  </form>
                </td>
              </tr>

              {{-- Modal Edit --}}
              <div class="modal fade" id="editModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content" style="background-color: var(--secondary-bg); color: var(--text-primary);">
                    <div class="modal-header border-secondary">
                      <h5 class="modal-title">Edit Produk</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="{{ route('admin.produk.update', $p->id) }}" enctype="multipart/form-data">
                      @csrf
                      @method('PUT')

                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label">Nama Produk</label>
                            <input name="name" type="text" class="form-control" value="{{ $p->name }}" required>
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input name="category" type="text" class="form-control" value="{{ $p->category }}" required>
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Harga</label>
                            <input name="price" type="number" class="form-control" value="{{ (int)$p->price }}" required>
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Ganti Cover (opsional)</label>
                            <input name="cover" type="file" class="form-control" accept="image/*">
                            @if($p->cover)
                              <small class="text-secondary d-block mt-2">
                                Cover saat ini: <span class="text-light">{{ $p->cover }}</span>
                              </small>
                            @endif
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Team Slug (opsional)</label>
                            <input name="team_slug" type="text" class="form-control" value="{{ $p->team_slug }}">
                          </div>

                          <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ $p->description }}</textarea>
                          </div>
                        </div>
                      </div>

                      <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-light fw-bold">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="6" class="text-center text-secondary py-4">Belum ada produk.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $produks->links() }}
      </div>
    </section>
  </main>

  <footer class="border-top border-secondary text-center py-4 mt-5">
    <p class="text-secondary mb-0">&copy; 2025 F1 Grand Store Dashboard. Dibuat oleh Tim Admin.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
