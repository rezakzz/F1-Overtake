<header>
    <div class="container nav-container">
        <a href="{{ route('index') }}" class="logo">F1<span> Overtake</span></a>
    
        <nav class="nav-center"> 
            <ul>
                <li>
                    <a id="teams-btn" href="#">Tim</a>
                    <div id="teams-dropdown" class="dropdown">
                        <ul>
                            <li><a href="KatalogTeam_Ferrari.html"><img src="{{asset ('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}" alt="Ferrari Logo">Scuderia Ferrari</a></li>
                            <li><a href="KatalogTeam_Mercedes.html"><img src="{{asset ('images/Mercedes-Logo.svg.png') }}" alt="Mercedes Logo">Mercedes-AMG F1 Team</a></li>
                            <li><a href="KatalogTeam_ReBull.html"><img src="{{asset ('images/redbull.png') }}" alt="Red Bull Logo">Red Bull Racing</a></li>
                            <li><a href="KatalogTeam_Mclaren.html"><img src="{{asset ('images/mclaren.png') }}" alt="McLaren Logo">McLaren F1 Team</a></li>
                            <li><a href="KatalogTeam_Williams.html"><img src="{{asset ('images/williams.png') }}" alt="Williams Logo">Williams Racing</a></li>
                            <li><a href="KatalogTeam_AstonMartin.html"><img src="{{asset ('images/astonmartin.png') }}" alt="Aston Martin Logo">Aston Martin F1 Team</a></li>
                            <li><a href="KatalogTeam_RacingBull.html"><img src="{{asset ('images/racingbullsrb.png') }}" alt="Racing Bulls Logo">Racing Bulls RB</a></li>
                            <li><a href="KatalogTeam_Alpine.html"><img src="{{asset ('images/Alpinelogo2.png') }}" alt="Alpine Logo">Alpine F1 Team</a></li>
                            <li><a href="KatalogTeam_Haas.html"><img src="{{asset ('images/haas.png') }}" alt="Haas Logo">Haas F1 Team</a></li>
                            <li><a href="KatalogTeam_KickSauber.html"><img src="{{asset ('images/kicksauber.png') }}" alt="Kick Sauber Logo">Kick Sauber</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a id="driver-btn" href="#">Pembalap</a>
                    <div id="driver-dropdown" class="dropdown">
                        <ul>
                            <a href="KatalogDriver_Leclerc.html"><li><img src="{{asset ('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}" alt="Ferrari Logo">Charles Leclerc</li></a>
                            <a href="KatalogDriver_Hamilton.html"><li><img src="{{asset ('images/ferrari-emblem-logo-vector-11574121617ycsermualj-removebg-preview.png') }}" alt="Ferrari Logo">Lewis Hamilton</li></a>
                            <a href="KatalogDriver_Max.html"><li><img src="{{asset ('images/redbull.png') }}" alt="Red Bull Logo">Max Verstappen</li></a>
                            <a href="KatalogDriver_Lando.html"><li><img src="{{asset ('images/mclaren.png') }}" alt="McLaren Logo">Lando Norris</li></a>
                            
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    
        <div class="nav-icons">
            <span onclick="openSearchModal()">Cari 🔍</span>
            <span onclick="openCartModal()">Keranjang 🛒</span>

            <span>
                <a href="#" class="login-icon-link" onclick="openModal(); return false;">
                    Login
                    <img src="{{ asset('images/login3bg.png') }}" alt="Ikon Akun" class="login-icon">
                </a>
            </span>
        </div>
    </div>
</header>