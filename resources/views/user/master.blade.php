<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic SEO -->
    <title>@yield('title', config('app.name', 'Codmshop'))</title>
    <meta name="description" content="@yield('meta_description', 'Best Free Fire Top Up & Gaming Shop in Bangladesh - Codmshop')">
    <meta name="keywords" content="@yield('meta_keywords', 'Free Fire Top Up, Codmshop, Gaming Shop, Diamond Recharge')">
    <meta name="author" content="Codmshop">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">

    <!-- Open Graph (Facebook / Social Sharing) -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', config('app.name', 'Codmshop'))">
    <meta property="og:description" content="@yield('og_description', 'Fast & Secure Free Fire Diamond Top Up in Bangladesh')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/og-default.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Codmshop') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name', 'Codmshop'))">
    <meta name="twitter:description" content="@yield('twitter_description', 'Buy Free Fire Diamonds & Game Credits use Codmshop')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('assets/images/twitter-default.png'))">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

    <link rel="preload" href="{{ asset('assets/user/header.css?v=2') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/user/header.css?v=2') }}">
    </noscript>


@if (Request::is('/'))
        <link rel="preload" href="{{ asset('assets/user/home.css?v=129') }}" as="style" onload="this.rel='stylesheet'">
    @endif

    @if (Request::is('product*') || Request::is('thank-you*'))
    <link rel="preload" href="{{ asset('assets/user/product.css?v=12') }}" as="style" onload="this.rel='stylesheet'">
    @endif

    <link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" as="style" onload="this.rel='stylesheet'">

    @stack('head')
</head>
<body>

<header>
    <div class="nav-container">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="logo">{{ config('app.name', 'Codmshop') }}</a>

        @auth
            <!-- Wallet + Balance + Profile -->
            <a href="{{ url('/profile') }}" class="account-row">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path>
                    <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
                </svg>৳ {{ Auth::user()->wallet ?? 0 }}
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiU0_6Mf8AnR9ny0woh2-u7LcoB2oWrks8OpSQfhzA9xxfk9CL4oxNQnWjoxwkDJwwUnY&usqp=CAU" alt="user-profile-picture" class="profile-img">
            </a>
        @else
            <!-- Login button for guests -->
            <div class="wallet-balance">
                <a href="#" id="loginBtn">Login</a>
            </div>
        @endauth
    </div>
</header>


<!-- Modal -->
<div id="loginModal" class="google-login">
    <div class="card">
        <a href="{{ url('/auth/google/redirect') }}" class="btn-google-login">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512" class="google-icon"><path data-v-67dc65cc="" d="M3.88 10.78A5.54 5.54 0 0 1 3.58 9c0-.62.11-1.22.29-1.78L.96 4.96A9.008 9.008 0 0 0 0 9c0 1.45.35 2.82.96 4.04l2.92-2.26z" fill="#FBBC05" data-v-8b45d494=""></path>
            </svg>
            Login with Google
        </a>

        <span id="closeModal" class="close-btn">&times;</span>
    </div>
</div>


<main>
    @yield('content')
</main>

<div class="bottom-nav">
    <a href="{{ url('/') }}" class="nav-item">
        <span class="material-icons">home</span>
        <span class="nav-label">Home</span>
    </a>
    <a href="{{ url('/orders') }}" class="nav-item">
        <span class="material-icons">shopping_bag</span>
        <span class="nav-label">My Orders</span>
    </a>
    <a href="{{ url('/account') }}" class="nav-item">
        <span class="material-icons">person</span>
        <span class="nav-label">My Account</span>
    </a>
</div>
<script src="{{ asset('assets/user/loginModal.js') }}" defer></script>
@stack('scripts')
</body>
</html>
