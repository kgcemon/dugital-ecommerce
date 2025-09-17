@extends('user.master')

@section('title', "Deposit")

@section('content')
    <main style="padding: 30px 15px;">
        <div class="panelData" style="max-width: 450px; margin: auto; position: relative;">
            <h2 style="margin-bottom:20px;">Player Information</h2>

            <!-- Response Message -->
            <div id="responseMessage" style="display:none; margin-bottom:15px;"></div>

            <!-- Loader -->
            <div id="loader" style="display:none; position:absolute; top:0; left:0; right:0; bottom:0;
             background:rgba(0,0,0,0.6); border-radius:12px; align-items:center; justify-content:center; flex-direction:column; z-index:10; color:#fff; font-weight:600; font-size:14px;">
                <div class="spinner" style="border:4px solid rgba(255,255,255,0.2); border-top:4px solid #00d4ff;
                 border-radius:50%; width:40px; height:40px; animation:spin 1s linear infinite; margin-bottom:10px;">
                </div>
                <div>Loading... <span id="loadingCount">0</span></div>
            </div>

            <form id="likeForm" method="POST">
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
                        <option value="sg">BD</option>
                        <option value="me">ME</option>
                        <option value="th">TH</option>
                        <option value="vn">VN</option>
                        <option value="us">US</option>
                        <option value="br">BR</option>
                        <option value="sac">SAC</option>
                    </select>
                </div>

                <!-- Submit -->
                <button type="submit"
                        style="width:100%;padding:12px;background:linear-gradient(135deg,#00c6ff,#0072ff);
                border:none;border-radius:8px;color:white;font-weight:600;cursor:pointer;transition:.3s;">
                    Submit
                </button>
            </form>
        </div>
    </main>

    <!-- Loader & Animations -->
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 0.8; }
        }
        #loadingCount { animation: pulse 1s infinite; }

        /* Response card styles */
        #responseMessage .card {
            width: 100%;
            border-radius: 20px;
            padding: 20px;
            background: linear-gradient(145deg,#28a745,#20c997);
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            font-family: 'Arial', sans-serif;
            position: relative;
        }
        #responseMessage .card.failed {
            background: linear-gradient(145deg,#dc3545,#ff6b6b);
        }
        #responseMessage .card .site-brand {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fff;
            display: block;
            text-align: center;
        }
        #responseMessage .card .player-info {
            margin-bottom: 10px;
            font-size: 14px;
        }
        #responseMessage .card .likes {
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
        }
        #responseMessage .card .footer {
            margin-top: 10px;
            font-size: 12px;
            opacity: 0.8;
            text-align: center;
        }
    </style>

    <!-- JS Script -->
    <script>
        let countInterval;

        document.getElementById("likeForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let form = this;
            let loader = document.getElementById("loader");
            let responseMessage = document.getElementById("responseMessage");
            let loadingCount = document.getElementById("loadingCount");

            let playerId = form.player_id.value.trim();
            if (!/^[0-9]+$/.test(playerId)) {
                showMessage("Player ID অবশ্যই শুধু সংখ্যা হবে!", true);
                return;
            }
            if (playerId.length < 5 || playerId.length > 13) {
                showMessage("Player ID কমপক্ষে 5 digit এবং সর্বোচ্চ 13 digit হতে হবে!", true);
                return;
            }

            let counter = 0;
            loadingCount.textContent = counter;
            loader.style.display = "flex";
            responseMessage.style.display = "none";

            countInterval = setInterval(() => {
                counter++;
                loadingCount.textContent = counter;
            }, 1000);

            fetch("{{ route('player.submit') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    player_id: playerId,
                    region: form.region.value
                })
            })
                .then(res => res.json())
                .then(data => {
                    loader.style.display = "none";
                    clearInterval(countInterval);

                    if (data.success && data.data) {
                        let d = data.data;

                        // failed case
                        if (d.failed_likes === 0) {
                            showMessage("আপনি আজকে আর লাইক নিতে পারবেন না, আগামিকাল চেষ্টা করুন ধন্যবাদ।", true);
                        }
                        // success card
                        else if (typeof d.likes_added !== "undefined") {
                            let added = d.likes_after - d.likes_before;
                            let nextTry = "আগামীকাল চেষ্টা করুন";

                            let html = `
                <div class="card">
                    <span class="site-brand">Codmshop.Com</span>
                    <div class="player-info"><strong>Name:</strong> ${d.name}</div>
                    <div class="player-info"><strong>Region:</strong> ${d.region}</div>
                    <div class="player-info"><strong>Level:</strong> ${d.level}</div>
                    <div class="likes"><strong>Likes Before:</strong> ${d.likes_before}</div>
                    <div class="likes"><strong>Likes Added:</strong> ${added}</div>
                    <div class="likes"><strong>Likes After:</strong> ${d.likes_after}</div>
                    <div class="footer">${added === 0 ? "⚠️ আজ কোনো লাইক যোগ হয়নি।" : ""} ${nextTry}</div>
                </div>
                `;
                            showMessage(html, false);
                        } else {
                            showMessage("Server error, please try again.", true);
                        }
                    } else {
                        showMessage("Something went wrong!", true);
                    }
                })
                .catch(err => {
                    loader.style.display = "none";
                    clearInterval(countInterval);
                    showMessage("Server error, please try again.", true);
                });

            function showMessage(msg, failed){
                responseMessage.style.display = "block";
                responseMessage.innerHTML = failed ? `<div class="card failed">${msg}</div>` : msg;
            }
        });
    </script>
@endsection
