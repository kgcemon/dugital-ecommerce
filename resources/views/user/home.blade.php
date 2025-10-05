@extends('user.master')

@section('title', "Codzshop | Free Fire Top Up in Bangladesh ")
@section('meta_description', 'Codzshop is a trusted Free Fire Diamond top up website in Bangladesh. Here you can easily recharge your favorite games like Free Fire Diamonds and more')
@section('meta_keywords', 'Codzshop,ff top up, top up, codm shop bd')

@section('content')

    <div class="banner">
       <a href="{{$images->link ?? ''}}">
           <img src="/storage/{{$images->images_url ?? ''}}" width="1200" height="400"
                alt="Premium Banner">
       </a>
    </div>

    @foreach ($products as $category)
        @if(count($category['products']) > 0)
            <div class="header">
                <h2 class="header-title">{{ strtoupper($category['name']) }}</h2>
            </div>
            <div class="container">
                @foreach ($category['products'] as $product)
                    <a href="{{ url('/product/' . $product['slug']) }}">
                        <div class="card">
                            <img src="{{ asset($product['image']) }}" alt="{{ $product['slug'] }}">
                            <div class="card-title">{{ $product['name'] }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach


    <div class="card" style="margin: 15px">
        <h1>Why choice top up use Codzshop?</h1>
        <div class="card-title"><p>
                <strong>Codzshop</strong> is a trusted Free Fire Diamond top up website in Bangladesh. Here you can easily recharge your favorite games like Free Fire Diamonds and more. We provide fast and secure gaming credits using bKash, Nagad, Rocket and other local payment methods. <strong>Diamonds</strong>
            </p></div>
    </div>

    <div class="card" style="margin: 15px">
        <h2>Delivery Time</h2>
        <div class="card-title"><p>
                <strong>Free Fire Top Up </strong> is intent delivery 1/10 second times need to delivery no need extra charge
            </p></div>
    </div>

    <div class="card" style="margin: 15px">
        <h2>Support</h2>
        <div class="card-title"><p>
                Call: 01828861788 <br>
                whatsApp: 01828861788 <br>
                facebook : <a href="https://m.me/Codzshop">Codzshop</a>
            </p></div>
    </div>
    <br>

    <div class="card">
        <a href="/about" class="nav-item">
            <span class="nav-label">About</span>
        </a>
        <a href="/privacy" class="nav-item">
            <span class="nav-label">Privacy</span>
        </a>
        <a href="/terms" class="nav-item active">
            <span class="nav-label">Terms</span>
        </a>
    </div>
    <br><br>
    <br>



@endsection

@push('scripts')
    <script src="{{ asset('assets/user/pwaAppV2.js') }}" defer></script>
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Store",
          "name": "Codzshop",
          "url": "https://codzshop.com",
          "logo": "https://codzshop.com/logo.png",
          "image": "https://codzshop.com/assets/banner.webp",
          "description": "Codzshop.com is Bangladesh's trusted gaming top-up service for Free Fire, PUBG, MLBB, and other popular games. Fast delivery, secure payment, and 24/7 support.",
          "address": {
            "@type": "Dhaka Mirpur 2” ,
            "addressCountry": "Bangladesh"
          },
          "telephone": "+88018288”61788”,
          "email": "support@codzshop.com",
          "sameAs": [
            "https://facebook.com/codzshop"
          ],
          "priceRange": "৳৳",
          "makesOffer": {
            "@type": "OfferCatalog",
            "name": "Game Top-Up Services",
            "itemListElement": [
              {
                "@type": "Offer",
                "itemOffered": {
                  "@type": "Service",
                  "name": "Free Fire Diamond Top-Up",
                  "description": "Instant Free Fire diamond recharge in Bangladesh."
                },
                "priceCurrency": "BDT"
              },
              {
                "@type": "Offer",
                "itemOffered": {
                  "@type": "Service",
                  "name": "PUBG UC Top-Up",
                  "description": "Buy PUBG Mobile UC safely and quickly from Codzshop."
                },
                "priceCurrency": "BDT"
              }
            ]
          }
        }
    </script>
@endpush
