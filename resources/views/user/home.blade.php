@extends('user.master')

@section('title', 'Home - Codmshop')

@section('content')

    <div class="banner">
        <img src="https://dl.dir.freefiremobile.com/common/web_event/official2.ff.garena.all/202210/aa959aa3d8790d3a44f7f20f16adfa01.jpg"
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
    <div> <br> <br> <br></div>
@endsection

@push('scripts')
    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Custom popup বানানো
            const popup = document.createElement('div');
            popup.innerHTML = `
        <div id="pwa-popup" style="
            position: fixed;
            bottom: 70px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg,#0F0C29,#302B63,#24243e);
            color: #fff;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.4);
            z-index: 2000;
            width: 90%;
            max-width: 350px;
            text-align: center;
            font-family: 'Inter', sans-serif;
            animation: slideUp 0.4s ease;
        ">
            <h3 style="margin-bottom: 10px; font-size: 1rem;">Install Codmshop</h3>
            <p style="font-size: 0.9rem; margin-bottom: 15px;">Add this app to your home screen for faster access.</p>
            <button id="installBtn" style="
                background: #00d4ff;
                border: none;
                padding: 10px 18px;
                border-radius: 8px;
                font-weight: bold;
                cursor: pointer;
                color: #000;
                margin-right: 10px;
            ">Install</button>
            <button id="closeBtn" style="
                background: transparent;
                border: 1px solid #fff;
                padding: 10px 18px;
                border-radius: 8px;
                cursor: pointer;
                color: #fff;
            ">Later</button>
        </div>
        <style>
        @keyframes slideUp {
            from { transform: translate(-50%, 100%); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }
        </style>
    `;
            document.body.appendChild(popup);

            // Install button click
            document.getElementById('installBtn').addEventListener('click', async () => {
                popup.remove();
                deferredPrompt.prompt();
                const choice = await deferredPrompt.userChoice;
                console.log('User choice:', choice.outcome);
                deferredPrompt = null;
            });

            // Close button click
            document.getElementById('closeBtn').addEventListener('click', () => {
                popup.remove();
            });
        });
    </script>

@endpush
