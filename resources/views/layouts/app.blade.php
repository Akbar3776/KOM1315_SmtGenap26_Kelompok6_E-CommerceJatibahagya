<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;700&display=swap"
        rel="stylesheet" />

    {{-- AOS CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    {{-- Bootwatch --}}
    <link href="https://bootswatch.com/5/zephyr/bootstrap.min.css" rel="stylesheet" />

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    <style>
        body {
            font-family: "Montserrat", sans-serif;
        }

        .navbar-custom {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            color: #0d6efd;
            /* Warna primary Bootstrap */
            font-weight: bold;
        }

        .search-bar {
            max-width: 400px;
            margin: 0 auto;
        }

        .carousel-item {
            height: 400px;
            /* Tinggi carousel */
            background-size: cover;
            background-position: center;
        }

        .carousel-item h1 {
            font-size: 3rem;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .carousel-item p {
            font-size: 1.5rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        @media (max-width: 768px) {
            .search-bar {
                width: 100%;
                margin: 10px 0;
            }

            .navbar-custom .navbar-brand {
                margin-right: auto;
            }

            .navbar-custom .d-flex {
                width: 100%;
                justify-content: space-between;
                margin-top: 10px;
            }

            .carousel-item {
                height: 300px;
                /* Tinggi carousel untuk mobile */
            }

            .carousel-item h1 {
                font-size: 2rem;
            }

            .carousel-item p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div id="app">

        {{-- Header --}}
        <div class="bg-light py-2 d-none d-md-block">
            <div class="container d-flex justify-content-between">
                <div>
                    <a href="{{ route('landing') }}" class="text-muted me-3">Beranda</a>
                    <a href="{{ route('products.all') }}" class="text-muted me-3">Produk</a>
                    <a href="#" class="text-muted">Bantuan</a>
                </div>
                <div>
                    <a href="#" class="text-muted me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-muted me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-muted"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>

        {{-- Navbar --}}
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container d-flex justify-content-center">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <!-- Tombol Toggler untuk Mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Search Bar dan Tombol Masuk & Daftar -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Search Bar di Tengah -->
                    <div class="search-bar">
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Cari..." aria-label="Search" />
                            <button class="btn btn-outline-primary" type="submit">
                                Cari
                            </button>
                        </form>
                    </div>

                    <!-- Tombol Masuk & Daftar di Kanan -->
                    <div class="d-flex justify-items-center align-items-center">
                        <a href="" class="btn position-relative me-3">
                            <i class="bi bi-cart"></i>
                            @if (session('cart') && count(session('cart')) > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ count(session('cart')) }}
                                </span>
                            @else
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    0
                                </span>
                            @endif
                        </a>
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Masuk</a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                            @endif
                        @else
                            <a class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </a>
                        @endguest
                    </div>
                </div>

            </div>
        </nav>

        <main class="py-0 d-flex flex-column min-vh-100">
            @yield('content')
        </main>

        <div class="container">
            <footer class="py-3 my-4">
                <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                    <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Home</a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Features</a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Pricing</a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQs</a></li>
                    <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>
                </ul>
                <p class="text-center text-muted">© 2021 Company, Inc</p>
            </footer>
        </div>
    </div>

    <!-- AOS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>
