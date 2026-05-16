<header>
    <div class="container nav-container">
        <a href="{{ route('home') }}" class="logo">F1<span> Overtake</span></a>

        <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false">
            ☰
        </button>

        <nav class="nav-center">
            <ul>
                <li>
                    <a id="teams-btn" href="#">Tim</a>
                    <div id="teams-dropdown" class="dropdown">
                        <ul>
                            <li><a href="{{ route('landing.Katalog', 'ferrari') }}"><img
                                        src="{{asset('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}"
                                        alt="Ferrari Logo">Scuderia Ferrari</a></li>
                            <li><a href="{{ route('landing.Katalog', 'mercedes') }}"><img
                                        src="{{asset('images/Mercedes-Logo.svg.png') }}"
                                        alt="Mercedes Logo">Mercedes-AMG F1 Team</a></li>
                            <li><a href="{{ route('landing.Katalog', 'red-bull-racing') }}"><img
                                        src="{{asset('images/redbull.png') }}" alt="Red Bull Logo">Red Bull Racing</a>
                            </li>
                            <li><a href="{{ route('landing.Katalog', 'mclaren') }}"><img
                                        src="{{asset('images/mclaren.png') }}" alt="McLaren Logo">McLaren F1 Team</a>
                            </li>
                            <li><a href="{{ route('landing.Katalog', 'williams-racing') }}"><img
                                        src="{{asset('images/williams.png') }}" alt="Williams Logo">Williams Racing</a>
                            </li>
                            <li><a href="{{ route('landing.Katalog', 'aston-martin') }}"><img
                                        src="{{asset('images/astonmartin.png') }}" alt="Aston Martin Logo">Aston Martin
                                    F1 Team</a></li>
                            <li><a href="{{ route('landing.Katalog', 'racingbulls-rb') }}"><img
                                        src="{{asset('images/racingbullsrb.png') }}" alt="Racing Bulls Logo">Racing
                                    Bulls RB</a></li>
                            <li><a href="{{ route('landing.Katalog', 'alpine') }}"><img
                                        src="{{asset('images/Alpinelogo2.png') }}" alt="Alpine Logo">Alpine F1 Team</a>
                            </li>
                            <li><a href="{{ route('landing.Katalog', 'haas') }}"><img
                                        src="{{asset('images/haas.png') }}" alt="Haas Logo">Haas F1 Team</a></li>
                            <li><a href="{{ route('landing.Katalog', 'kicksauber') }}"><img
                                        src="{{asset('images/kicksauber.png') }}" alt="Kick Sauber Logo">Kick
                                    Sauber</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a id="driver-btn" href="#">Pembalap</a>
                    <div id="driver-dropdown" class="dropdown">
                        <ul>
                            <a href="{{ route('landing.Katalog', 'ferrari') }}">
                                <li><img src="{{asset('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}"
                                        alt="Ferrari Logo">Charles Leclerc</li>
                            </a>
                            <a href="{{ route('landing.Katalog', 'ferrari') }}">
                                <li><img src="{{asset('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}"
                                        alt="Ferrari Logo">Lewis Hamilton</li>
                            </a>
                            <a href="{{ route('landing.Katalog', 'red-bull-racing') }}">
                                <li><img src="{{asset('images/redbull.png') }}" alt="Red Bull Logo">Max Verstappen</li>
                            </a>
                            <a href="{{ route('landing.Katalog', 'mclaren') }}">
                                <li><img src="{{asset('images/mclaren.png') }}" alt="McLaren Logo">Lando Norris</li>
                            </a>

                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        <div class="nav-icons">
            <span onclick="openSearchModal()" style="cursor:pointer;">Cari 🔍</span>
            <span onclick="openCartModal()" style="cursor:pointer;">Keranjang 🛒</span>

            @guest
                <span>
                    <a href="#" class="login-icon-link" onclick="openModal(); return false;">
                        Masuk
                        <img src="{{ asset('images/login3bg.png') }}" alt="Akun" class="login-icon">
                    </a>
                </span>

            @endguest

            @auth
                <span style="position: relative; display: inline-block;">
                    <a href="#" onclick="toggleLogoutMenu(event)"
                        style="font-weight: bold; color: #e10600; text-decoration: none;">
                        Hi, {{ Auth::user()->name }} ▼
                    </a>
                    <div id="logout-dropdown"
                        style="display: none; position: absolute; right: 0; top: 30px; background: #1A1A1E; border: 1px solid #333; padding: 10px; border-radius: 5px; min-width: 160px; z-index: 1000;">

                        @php
                            $roleRaw = (string) (Auth::user()->role ?? '');
                            $role = strtolower(trim($roleRaw));
                            $role = str_replace([' ', '-'], '_', $role);
                            if ($role === 'superadmin')
                                $role = 'super_admin';

                            $canAccessAdmin = in_array($role, ['admin', 'super_admin', 'staff', 'viewer'], true);
                        @endphp

                        @if($canAccessAdmin)
                            <a href="{{ route('admin.dashboard') }}"
                                class="dropdown-item d-flex align-items-center justify-content-between"
                                style="display:block; padding: 6px 8px; color: white; text-decoration: none; font-size: 0.9rem;">
                                <span>Dashboard Admin</span>
                                <span class="ms-2">🛠️</span>
                            </a>

                            <div style="border-top: 1px solid #333; margin: 8px 0;"></div>
                        @endif

                        <a href="{{ route('orders.my') }}"
                            class="dropdown-item d-flex align-items-center justify-content-between"
                            style="display:block; padding: 6px 8px; color: white; text-decoration: none; font-size: 0.9rem;">
                            <span>Riwayat Pesanan</span>
                            <span class="ms-2">🧾</span>
                        </a>

                        <div style="border-top: 1px solid #333; margin: 8px 0;"></div>

                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="d-flex align-items-center justify-content-between"
                                style="background: none; border: none; color: white; cursor: pointer; width: 100%; text-align: left; font-size: 0.9rem; padding: 6px 8px;">
                                <span>Logout</span>
                                <span class="ms-2">🚪</span>
                            </button>
                        </form>
                    </div>
                </span>
            @endauth
        </div>
        <div class="mobile-menu" id="mobileMenu">

            <button type="button" class="m-dd-btn" data-target="mTeamsList">
                Tim <span>▾</span>
            </button>
            <div class="m-dd-panel" id="mTeamsList">
                <ul class="m-list">
                    <li>
                        <a href="{{ route('landing.Katalog', 'ferrari') }}">
                            <img src="{{ asset('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}"
                                alt="Ferrari Logo">
                            <span>Scuderia Ferrari</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'mercedes') }}">
                            <img src="{{ asset('images/Mercedes-Logo.svg.png') }}" alt="Mercedes Logo">
                            <span>Mercedes-AMG F1 Team</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'red-bull-racing') }}">
                            <img src="{{ asset('images/redbull.png') }}" alt="Red Bull Logo">
                            <span>Red Bull Racing</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'mclaren') }}">
                            <img src="{{ asset('images/mclaren.png') }}" alt="McLaren Logo">
                            <span>McLaren F1 Team</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'williams-racing') }}">
                            <img src="{{ asset('images/williams.png') }}" alt="Williams Logo">
                            <span>Williams Racing</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'aston-martin') }}">
                            <img src="{{ asset('images/astonmartin.png') }}" alt="Aston Martin Logo">
                            <span>Aston Martin F1 Team</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'racingbulls-rb') }}">
                            <img src="{{ asset('images/racingbullsrb.png') }}" alt="Racing Bulls Logo">
                            <span>Racing Bulls RB</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'alpine') }}">
                            <img src="{{ asset('images/Alpinelogo2.png') }}" alt="Alpine Logo">
                            <span>Alpine F1 Team</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'haas') }}">
                            <img src="{{ asset('images/haas.png') }}" alt="Haas Logo">
                            <span>Haas F1 Team</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'kicksauber') }}">
                            <img src="{{ asset('images/kicksauber.png') }}" alt="Kick Sauber Logo">
                            <span>Kick Sauber</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- PEMBALAP -->
            <button class="m-dd-btn" type="button" data-target="mDriversList">
                Pembalap <span class="m-dd-icon">▾</span>
            </button>
            <div class="m-dd-panel" id="mDriversList">
                <ul class="m-list">
                    <li>
                        <a href="{{ route('landing.Katalog', 'ferrari') }}">
                            <img src="{{ asset('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}"
                                alt="Ferrari Logo">
                            <span>Charles Leclerc</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'ferrari') }}">
                            <img src="{{ asset('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}"
                                alt="Ferrari Logo">
                            <span>Lewis Hamilton</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'red-bull-racing') }}">
                            <img src="{{ asset('images/redbull.png') }}" alt="Red Bull Logo">
                            <span>Max Verstappen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.Katalog', 'mclaren') }}">
                            <img src="{{ asset('images/mclaren.png') }}" alt="McLaren Logo">
                            <span>Lando Norris</span>
                        </a>
                    </li>
                </ul>
            </div>


            <!-- sisanya tetap -->
            <a class="m-link" href="#" onclick="openSearchModal(); return false;">Cari 🔍</a>
            <a class="m-link" href="#" onclick="openCartModal(); return false;">Keranjang 🛒</a>

            @guest
                <a class="m-link" href="#" onclick="openModal(); return false;">Masuk 👤</a>
            @endguest

            @auth
                <button class="m-dd-btn m-user-btn" type="button" data-target="mUserMenu">
                    Hi, {{ Auth::user()->name }} <span>▾</span>
                </button>

                <div class="m-dd-panel" id="mUserMenu">
                    <ul class="m-list">
                        <li>
                            <a href="{{ route('orders.my') }}">
                                <span>Riwayat Pesanan</span>
                                <span style="margin-left:auto;">🧾</span>
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="mobile-logout-btn m-dd-logout-btn">
                                    <span>Logout</span>
                                    <span style="margin-left:auto;">🚪</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>

    </div>
</header>

<script>
    function toggleLogoutMenu(e) {
        e.preventDefault();
        const menu = document.getElementById('logout-dropdown');
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }

    window.addEventListener('click', function (e) {
        const menu = document.getElementById('logout-dropdown');
        if (menu && !e.target.closest('span[style*="relative"]')) {
            menu.style.display = 'none';
        }
    });
</script>