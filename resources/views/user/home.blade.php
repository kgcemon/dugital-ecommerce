@extends('user.master')

@section('title', "Free Fire Diamond Top Up BD bKash | Instant Delivery - Codzshop")
@section('meta_description', 'Buy Free Fire Diamond Top Up BD bKash at the cheapest price. Get instant delivery for FF Diamonds in Bangladesh using bKash, Nagad, and Rocket safely.')
@section('meta_keywords', 'free fire diamond top up bd bkash, ff top up bd bkash, free fire diamond buy bangladesh, codzshop ff top up, cheap ff diamond bd')

@section('content')

    {{-- Banner Section with Alt Text for SEO --}}
    <div class="banner">
        <a href="{{$images->link ?? ''}}">
            <img src="{{$images->images_url ?? ''}}" width="1200" height="400"
                 alt="Free Fire Diamond Top Up BD bKash - Best Deals on Codzshop">
        </a>
    </div>

    {{-- Product Categories --}}
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

    {{-- SEO Optimized Content Section --}}
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
    <div class="card" style="display: flex; justify-content: space-around; padding: 10px;">
        <a href="/about" class="nav-item">About Us</a>
        <a href="/privacy" class="nav-item">Privacy Policy</a>
        <a href="/terms" class="nav-item active">Terms & Conditions</a>
    </div>
    <br><br>

@endsection

@push('scripts')
    <script src="{{ asset('assets/user/pwaAppV3.js') }}" defer></script>

    {{-- JSON-LD Schema Markup (Highly Recommended for Google Ranking) --}}
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Codzshop",
          "url": "https://codzshop.com",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "https://codzshop.com/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        }
    </script>

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Store",
          "name": "Codzshop",
          "url": "https://codzshop.com",
          "logo": "https://codzshop.com/logo.png",
          "description": "Trusted Free Fire Diamond Top Up BD bKash website. Get instant FF diamonds recharge via bKash, Nagad, and Rocket in Bangladesh.",
          "telephone": "+8801828861788",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Mirpur 2",
            "addressLocality": "Dhaka",
            "addressCountry": "BD"
          }
        }
    </script>
@endpush
