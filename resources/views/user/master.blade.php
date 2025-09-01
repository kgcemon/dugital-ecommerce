<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#131031">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="https://codmshop.com/logo.png">

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

    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{
            font-family:'Inter',sans-serif;
            background:linear-gradient(135deg,#0F0C29 0%,#302B63 50%,#24243e 100%);
            min-height:100vh;overflow-x:hidden;position:relative;color:white;
        }

        .panelData{
            background:linear-gradient(145deg,rgba(255,255,255,.08),rgba(255,255,255,.04));
            backdrop-filter:blur(20px);
            border:1px solid rgba(255,255,255,.2);
            border-radius:8px;
            padding:25px 20px 20px 20px;
            margin-bottom:25px;
            text-align:center;
            position:relative;
            transition: .3s;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
        .account-row {
            display: flex;
            align-items: center;  /* vertical center */
            gap: 10px;            /* space between icon, balance, image */
            text-decoration: none;
        }

        .wallet-balance {
            background-color: #28a745; /* green */
            color: white;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        /* Header & Navigation */
        header {
            width: 100%;
            background: linear-gradient(135deg, rgba(15,12,41,0.95) 0%, rgba(48,43,99,0.9) 100%);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            padding: 5px;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        nav ul li a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            padding: 8px 15px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            color: #00d4ff;
            background: rgba(0,212,255,0.1);
            transform: translateY(-2px);
        }

        /* Bottom Navigation Bar */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(135deg, rgba(15,12,41,0.95), rgba(48,43,99,0.9));
            backdrop-filter: blur(15px);
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            z-index: 999;
        }

        .bottom-nav .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            font-size: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .bottom-nav .nav-item:hover {
            color: #00d4ff;
        }

        .bottom-nav .material-icons {
            font-size: 24px;
            margin-bottom: 2px;
        }

        /* Responsive for Header/Nav */
        @media (max-width: 1024px) {
            nav ul { gap: 15px; }
            nav ul li a { font-size: 0.95rem; padding: 7px 12px; }
            .bottom-nav .material-icons { font-size: 22px; }
        }

        @media (max-width: 600px) {
            nav ul { gap: 12px; }
            nav ul li a { font-size: 0.9rem; padding: 6px 12px; }
            .bottom-nav .material-icons { font-size: 20px; }
            .bottom-nav .nav-label { font-size: 0.7rem; }
        }

        @media (max-width: 400px) {
            nav ul li a { font-size: 0.85rem; }
        }

        /* Modal overlay */
        .google-login {
            display: none;
            position: fixed; /* eta thik ache */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000; /* child card er z-index er niche thakbe */
        }


        /* Show modal */
        .google-login.show {
            display: flex;
        }

        /* Close button */
        .close-btn {
            position: absolute;
            top:10px;
            right:15px;
            cursor: pointer;
            font-weight: bold;
            font-size: 20px;
        }

        /* Google login button */
        .btn-google-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            padding: 10px 16px;
            background: #DB4437;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        /* Google SVG icon */
        .google-icon {
            width: 20px;
            height: 20px;
            margin-right: 8px;
        }
    </style>


@if (Request::is('/'))
        <link rel="stylesheet" href="{{ asset('assets/user/home.css?v=133') }}" media="print" onload="this.media='all'">
        <noscript><link rel="stylesheet" href="{{ asset('assets/user/home.css?v=133') }}"></noscript>
    @endif

    @if (Request::is('product*') || Request::is('thank-you*'))
    <link rel="stylesheet" href="{{ asset('assets/user/product.css?v=14') }}" as="style" onload="this.rel='stylesheet'">
    @endif
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
                <img src="{{ Auth::user()->image}}" alt="user-profile-picture" class="profile-img">
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
            <svg class="google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 533.5 544.3" width="20" height="20">
                <path fill="#4285F4" d="M533.5 278.4c0-18.4-1.5-36.1-4.3-53.4H272v101h146.9c-6.4 34.5-25.3 63.8-54 83.5v69.4h87.1c50.8-46.8 80.5-115.9 80.5-200.5z"/>
                <path fill="#34A853" d="M272 544.3c73.3 0 134.9-24.2 179.8-65.7l-87.1-69.4c-24.2 16.2-55.1 25.7-92.7 25.7-71 0-131-47.9-152.3-112.3H30.9v70.9C75.7 485.3 167.2 544.3 272 544.3z"/>
                <path fill="#FBBC05" d="M119.7 320.6c-10.6-31.3-10.6-64.6 0-95.9V153.8H30.9c-43.7 87.2-43.7 190.1 0 277.3l88.8-70.5z"/>
                <path fill="#EA4335" d="M272 107.7c39.9-.6 77.8 14 106.7 40.4l80.1-80.1C406.8 24.7 345.3 0 272 0 167.2 0 75.7 58.9 30.9 153.8l88.8 70.9c21.3-64.4 81.3-112.3 152.3-116.9z"/>
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
        <!-- Home SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
        </svg>
        <span class="nav-label">Home</span>
    </a>

    <a href="{{ url('/orders') }}" class="nav-item">
        <!-- Shopping Bag SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6 2h12l1 4H5l1-4zm0 6h14v14H6V8z"/>
        </svg>
        <span class="nav-label">My Orders</span>
    </a>

    <a href="{{ url('/profile') }}" class="nav-item">
        <!-- Person SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
        </svg>
        <span class="nav-label">My Account</span>
    </a>
</div>

<script src="{{ asset('assets/user/loginModal.js') }}" defer></script>
@stack('scripts')
</body>
</html>
