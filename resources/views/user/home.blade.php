@extends('user.master')

@section('title', "Codmshop | Free Fire Top Up in Bangladesh ")
@section('meta_description', 'codmshop is a gaming credit top up system website from bangladesh top up popular games Like Free Fire Diamonds use bkash')
@section('meta_keywords', 'codmshop,ff top up, top up, codm shop bd')

@section('content')

    <div class="banner">
       <a href="{{$images->link ?? ''}}">
           <img src="/{{$images->images_url ?? ''}}" width="1200" height="400"
                alt="Premium Banner">
       </a>
    </div>

    @foreach ($products as $category)
        @if(count($category['products']) > 0)
            <div class="header">
                <h1 class="header-title">{{ strtoupper($category['name']) }}</h1>
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
        <h1>Why choice top up use Codmshop?</h1>
        <div class="card-title"><p>
                <strong>Codmshop</strong> is a must old and popular rop up shop in bangladesh we have many customers review in social media & we sell bangladesh best low prices Free Fire <strong>Diamonds</strong>
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
                facebook : <a href="https://m.me/codmshop">Codmshop</a>
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
    <script src="{{ asset('assets/user/pwaAppV1.js') }}" defer></script>
@endpush
