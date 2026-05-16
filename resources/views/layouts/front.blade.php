<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - F1 Overtake</title>
    @stack('before-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    @stack('after-styles')
</head>


<body>

    @yield('content')

    <div id="cart-modal" class="modal">
        <div class="modal-content cart-content">
            <span class="close-btn" onclick="closeCartModal()">&times;</span>
            <h2 class="modal-title">Keranjang Belanja</h2>

            <div id="cart-items-container">
                <div class="cart-item">
                    <img src="{{ asset('images/Kaus-Polo-Scuderia-Ferrari-2025-Team-Pria.jpg') }}" alt="Polo Ferrari"
                        class="cart-item-image">
                    <div class="cart-item-details">
                        <h4>Polo Ferrari 2025</h4>
                        <p>Jumlah: 1 | Ukuran: L</p>
                    </div>
                    <div class="item-price-and-remove">
                        <span class="item-price">Rp 1.450.000</span>
                        <button class="remove-item-btn">Hapus</button>
                    </div>
                </div>
                <div class="cart-item">
                    <img src="{{ asset('images/topimax.jpg') }}" alt="Topi Max" class="cart-item-image">
                    <div class="cart-item-details">
                        <h4>Topi Max Verstappen</h4>
                        <p>Jumlah: 1 | Warna: Biru</p>
                    </div>
                    <div class="item-price-and-remove">
                        <span class="item-price">Rp 990.000</span>
                        <button class="remove-item-btn">Hapus</button>
                    </div>
                </div>
            </div>

            <div class="cart-summary">
                <span>Total:</span>
                <span class="item-price">Rp 2.440.000</span>
            </div>

            <a href="{{ route('checkout.page') }}" class="btn btn-danger btn-lg w-100 mt-4">
                Checkout
            </a>
        </div>
    </div>

    <div id="search-modal" class="modal">
        <div class="modal-content search-content">
            <span class="close-btn" onclick="closeSearchModal()">&times;</span>
            <h2 class="modal-title">Cari Produk F1</h2>

            <form id="search-form" class="search-input-group">
                <input type="text" id="search-input" placeholder="Cari Tim, Pembalap, atau Produk..." required>
                <button type="submit" class="search-submit-btn btn-primary">Cari</button>
            </form>

            <div id="search-results-container">
                <div class="search-tip">Masukkan kata kunci untuk memulai pencarian.</div>
            </div>
        </div>
    </div>

    <div id="login-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>

            <div id="login-content">
                <h2 class="modal-title">MASUK</h2>
                <form id="login-form">
                    <div class="input-group">
                        <label for="username">Username/Email</label>
                        <input type="text" id="username" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" required>
                    </div>
                    <div class="forgot-wrap">
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary login-submit-btn">Masuk</button>
                </form>
                <div class="modal-footer text-center">
                    <p>Belum punya akun? <a href="#" id="switch-to-register-link">Daftar Sekarang</a></p>
                </div>
            </div>

            <div id="register-content" style="display: none;">
                <h2 class="modal-title">DAFTAR AKUN</h2>
                <form id="register-form">
                    <div class="input-group">
                        <label for="reg-name">Nama Lengkap</label>
                        <input type="text" id="reg-name" required>
                    </div>
                    <div class="input-group">
                        <label for="reg-email">Email</label>
                        <input type="email" id="reg-email" required>
                    </div>
                    <div class="input-group">
                        <label for="reg-password">Password</label>
                        <input type="password" id="reg-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary login-submit-btn">Daftar</button>
                </form>
                <div class="modal-footer text-center">
                    <p>Sudah punya akun? <a href="#" id="switch-to-login-link">Masuk</a></p>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" style="position:fixed; top:90px; right:20px; z-index:9999;"></div>
    @stack('before-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    @stack('after-scripts')
    <script src="{{ asset('js/dropdown_driver.js') }}"></script>
    <script src="{{ asset('js/dropdown_tim.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/navbar.js') }}"></script>

</body>

</html>