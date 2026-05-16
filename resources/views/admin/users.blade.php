<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen User & Role - F1 Store Super Admin</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/styleAdmin.css') }}">

  <style>
    body {
      background-color: var(--primary-bg) !important;
      color: var(--text-primary) !important;
    }
    .card, .table, .form-select, .form-control {
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
          <li class="nav-item"><a href="{{ route('admin.produk.index') }}" class="nav-link text-secondary">Produk</a></li>
          <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link text-light fw-bold">User & Role</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container py-5">
    <h2 class="section-title mb-5 text-center text-uppercase">Manajemen User & Role</h2>

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

    <div class="card p-4 rounded-4 shadow-sm">
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle rounded-3 overflow-hidden">
          <thead style="background-color:#1f1f23;">
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse ($users as $u)
              <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>

                <td style="min-width: 200px;">
                  <form class="d-flex gap-2" method="POST" action="{{ route('admin.users.role', $u->id) }}">
                    @csrf
                    @method('PUT')

                    <select name="role" class="form-select form-select-sm">
                      <option value="user"  @selected($u->role === 'user')>user</option>
                      <option value="admin" @selected($u->role === 'admin')>admin</option>
                    </select>
                </td>

                <td class="text-end">
                    <button type="submit" class="btn btn-outline-light btn-sm fw-bold">Simpan</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-secondary py-4">Belum ada user.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $users->links() }}
      </div>
    </div>
  </main>

  <footer class="border-top border-secondary text-center py-4 mt-5">
    <p class="text-secondary mb-0">&copy; 2025 F1 Grand Store Dashboard. Dibuat oleh Tim Admin.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
