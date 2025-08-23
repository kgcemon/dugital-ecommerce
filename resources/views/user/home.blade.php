@extends('user.master')

@section('title', 'Home - Codmshop')

@section('content')

    <div class="banner">
        <img src="https://admin.gosizi.com/notices/1745317224_home_page_footer_thumbnail.png"
             alt="Premium Banner">
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
                            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}">
                            <div class="card-title">{{ $product['name'] }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach

@endsection
