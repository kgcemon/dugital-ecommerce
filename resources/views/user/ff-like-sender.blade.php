@extends('user.master')

@section('title', "Deposit")

@section('content')
    <main style="padding: 30px 15px;">
        <div class="panelData" style="max-width: 400px; margin: auto;">
            <h2 style="margin-bottom:20px;">Player Information</h2>

            <form method="POST" action="{{ route('player.submit') }}">
                @csrf
                <!-- Player ID -->
                <div style="margin-bottom: 15px; text-align: left;">
                    <label for="player_id" style="font-weight:600;">Player ID</label>
                    <input type="text" id="player_id" name="player_id" class="form-control"
                           placeholder="Enter your Player ID" required
                           style="width:100%;padding:10px;border-radius:8px;border:none;outline:none;margin-top:5px;">
                </div>

                <!-- Region -->
                <div style="margin-bottom: 15px; text-align: left;">
                    <label for="region" style="font-weight:600;">Select Region</label>
                    <select id="region" name="region" required
                            style="width:100%;padding:10px;border-radius:8px;border:none;outline:none;margin-top:5px;">
                        <option value="">-- Choose Region --</option>
                        <option value="ME">ME</option>
                        <option value="SG">SG</option>
                        <option value="BD">BD</option>
                        <option value="TH">TH</option>
                        <option value="VN">VN</option>
                        <option value="US">US</option>
                        <option value="BR">BR</option>
                        <option value="SAC">SAC</option>
                        <option value="NA">NA</option>
                    </select>
                </div>

                <!-- Submit -->
                <button type="submit"
                        style="width:100%;padding:12px;background:linear-gradient(135deg,#00c6ff,#0072ff);border:none;border-radius:8px;color:white;font-weight:600;cursor:pointer;transition:.3s;">
                    Submit
                </button>
            </form>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="#" class="nav-item">
            <span class="material-icons">home</span>
            <span class="nav-label">Home</span>
        </a>
        <a href="#" class="nav-item">
            <span class="material-icons">person</span>
            <span class="nav-label">Account</span>
        </a>
        <a href="#" class="nav-item">
            <span class="material-icons">shopping_cart</span>
            <span class="nav-label">Orders</span>
        </a>
    </div>

@endsection
