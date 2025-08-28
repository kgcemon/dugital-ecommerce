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
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/user/header.css?v=129') }}">
    @if (Request::is('/'))
        <link rel="stylesheet" href="{{ asset('assets/user/home.css?v=129') }}">
    @endif

    @if (Request::is('product*') || Request::is('thank-you*'))
    <link rel="stylesheet" href="{{ asset('assets/user/product.css?v=10') }}">
        <style>
            .btn-loading {
                position: relative;
                pointer-events: none;
                opacity: 0.7;
            }
            .btn-loading::after {
                content: "";
                position: absolute;
                right: 12px;
                top: 50%;
                width: 18px;
                height: 18px;
                margin-top: -9px;
                border: 2px solid #fff;
                border-top-color: transparent;
                border-radius: 50%;
                animation: spin 0.7s linear infinite;
            }
            @keyframes spin {
                to { transform: rotate(360deg); }
            }

        .response-box {
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 15px;
                font-weight: 600;
                text-align: center;
            }
            .response-box.success { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }
            .response-box.error { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }

            .loading-spinner {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                margin: 15px 0;
            }
            .spinner {
                width: 40px;
                height: 40px;
                border: 4px solid #ddd;
                border-top: 4px solid #4f46e5;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    @endif

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    @stack('head')
</head>
<body>

<header>
    <div class="nav-container">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="logo">{{ config('app.name', 'Codmshop') }}</a>

        <!-- Auth Links -->
        <div class="auth-link">
            @auth
                <a href="{{ url('/account') }}">My Account</a>
{{--                <form method="POST" action="{{ route('logout') }}" style="display:inline;">--}}
{{--                    @csrf--}}
{{--                    <button type="submit" style="background:none;border:none;color:white;cursor:pointer;">Logout</button>--}}
{{--                </form>--}}
            @else
                <a href="{{ url('/login') }}">Login</a>
            @endauth
        </div>
    </div>
</header>

<!-- Main Content -->
<main>
    @yield('content')
</main>

<!-- Bottom Navigation Bar -->
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

@stack('scripts')
</body>
</html>
