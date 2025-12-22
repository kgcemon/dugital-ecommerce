@extends('user.master')

@section('title', "Free Fire Diamond Top Up BD bKash | Instant Delivery - Codzshop")
@section('meta_description', 'Buy Free Fire Diamond Top Up BD bKash at the cheapest price. Get instant delivery for FF Diamonds in Bangladesh using bKash, Nagad, and Rocket safely.')
@section('meta_keywords', 'free fire diamond top up bd bkash, ff top up bd bkash, free fire diamond buy bangladesh, codzshop ff top up, cheap ff diamond bd')

@section('content')

    <div class="banner">
        <a href="{{$images->link ?? ''}}">
            <img src="{{$images->images_url ?? ''}}" width="1200" height="400"
                 alt="Free Fire Diamond Top Up BD bKash - Best Deals on Codzshop">
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
                            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }} - Free Fire Top Up">
                            <div class="card-title">{{ $product['name'] }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach


    <div class="card" style="margin: 15px; padding: 20px;">
        <h1>Free Fire Diamond Top Up BD bKash</h1>
        <div class="card-title">
            <p>
                Looking for the best <strong>Free Fire Diamond top up BD bKash</strong> website? Welcome to Codzshop, the most trusted platform for gamers in Bangladesh. You can now easily <strong>top up Free Fire Diamonds</strong> using local payment methods like <strong>bKash, Nagad, and Rocket</strong>.
            </p>
            <p>
                We offer the cheapest <strong>FF Diamond</strong> rates with a secure and automated system. Whether you need weekly membership, monthly membership, or direct ID code top-up, we've got you covered.
            </p>
        </div>
    </div>

    <div class="card" style="margin: 15px; padding: 20px;">
        <h2>Fastest Delivery Time in Bangladesh</h2>
        <div class="card-title">
            <p>
                Our <strong>Free Fire Top Up</strong> service is popular for its instant delivery. Usually, it takes only <strong>1 to 10 seconds</strong> to receive your diamonds after a successful payment. No hidden charges or extra fees!
            </p>
        </div>
    </div>

    <div class="card" style="margin: 15px; padding: 20px;">
        <h2>24/7 Customer Support</h2>
        <div class="card-title">
            <p>
                If you face any issues with your <strong>Free Fire Diamond recharge</strong>, contact us immediately:
            </p>
            <p>
                <strong>Call:</strong> 01828861788 <br>
                <strong>WhatsApp:</strong> 01828861788 <br>
                <strong>Facebook:</strong> <a href="https://m.me/Codzshop" target="_blank" rel="noopener">Codzshop Official</a>
            </p>
        </div>
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
    <script src="{{ asset('assets/user/pwaAppV3.js') }}" defer></script>

    @verbatim
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
                "@type": "PostalAddress",
                "streetAddress": "Mirpur 2",
                "addressLocality": "Dhaka",
                "addressCountry": "Bangladesh"
              },
              "telephone": "+8801828861788",
              "email": "support@codzshop.com",
              "sameAs": ["https://facebook.com/codzshop"],
              "priceRange": "৳৳",
              "makesOffer": [
                {
                  "@type": "Offer",
                  "priceCurrency": "BDT",
                  "itemOffered": {
                    "@type": "Service",
                    "name": "Free Fire Diamond Top-Up",
                    "description": "Instant Free Fire diamond recharge in Bangladesh."
                  }
                },
                {
                  "@type": "Offer",
                  "priceCurrency": "BDT",
                  "itemOffered": {
                    "@type": "Service",
                    "name": "PUBG UC Top-Up",
                    "description": "Buy PUBG Mobile UC safely and quickly from Codzshop."
                  }
                }
              ]
            }
        </script>
    @endverbatim
@endpush
