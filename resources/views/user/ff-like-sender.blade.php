@extends('user.master')

@section('title', "Deposit")

@section('content')
    <main style="padding: 30px 15px;">
        <div class="panelData" style="max-width: 400px; margin: auto; position: relative;">
            <h2 style="margin-bottom:20px;">Player Information</h2>

            <!-- Response Message -->
            <div id="responseMessage" style="display:none; margin-bottom:15px; padding:10px; border-radius:6px; font-weight:600;"></div>

            <!-- Loader -->
            <div id="loader" style="display:none; position:absolute; top:0; left:0; right:0; bottom:0;
             background:rgba(0,0,0,0.6); border-radius:8px; align-items:center; justify-content:center; flex-direction:column; z-index:10; color:#fff; font-weight:600; font-size:14px;">
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
                        <option value="me">ME</option>
                        <option value="sg">SG</option>
                        <option value="bd">BD</option>
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

    <!-- Loader Animation -->
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

            // Validation: only numbers + length check
            let playerId = form.player_id.value.trim();
            if (!/^[0-9]+$/.test(playerId)) {
                responseMessage.style.display = "block";
                responseMessage.style.background = "rgba(220,53,69,0.85)";
                responseMessage.style.color = "#fff";
                responseMessage.innerHTML = "⚠️ Player ID অবশ্যই শুধু সংখ্যা হবে!";
                return;
            }
            if (playerId.length < 5 || playerId.length > 13) {
                responseMessage.style.display = "block";
                responseMessage.style.background = "rgba(220,53,69,0.85)";
                responseMessage.style.color = "#fff";
                responseMessage.innerHTML = "⚠️ Player ID কমপক্ষে 5 digit এবং সর্বোচ্চ 13 digit হতে হবে!";
                return;
            }

            let counter = 0;
            loadingCount.textContent = counter;
            loader.style.display = "flex";
            responseMessage.style.display = "none";

            // start counter
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

                    // ✅ Response handling
                    if (data.success) {
                        // Case 1: failed_likes === 0
                        if (typeof data.data.failed_likes !== "undefined" && data.data.failed_likes === 0) {
                            responseMessage.style.display = "block";
                            responseMessage.style.background = "rgba(220,53,69,0.85)";
                            responseMessage.style.color = "#fff";
                            responseMessage.innerHTML = "❌ আপনি আজকে আর লাইক নিতে পারবেন না, আগামিকাল চেষ্টা করুন ধন্যবাদ।";
                        }
                        // Case 2: likes_added পাওয়া গেছে
                        else if (typeof data.data.likes_added !== "undefined") {
                            responseMessage.style.display = "block";
                            responseMessage.style.background = "rgba(40,167,69,0.7)";
                            responseMessage.style.color = "#fff";
                            responseMessage.innerHTML = `
                                ✅ Success!<br>
                                Name: ${data.data.name ?? "-"}<br>
                                Likes Added: ${data.data.likes_added}
                            `;
                        }
                        // Case 3: Unexpected
                        else {
                            responseMessage.style.display = "block";
                            responseMessage.style.background = "rgba(220,53,69,0.85)";
                            responseMessage.style.color = "#fff";
                            responseMessage.innerHTML = "⚠️ Server error, please try again.";
                        }
                    } else {
                        responseMessage.style.display = "block";
                        responseMessage.style.background = "rgba(220,53,69,0.85)";
                        responseMessage.style.color = "#fff";
                        responseMessage.innerHTML = "❌ Something went wrong!";
                    }
                })
                .catch(err => {
                    loader.style.display = "none";
                    clearInterval(countInterval);

                    responseMessage.style.display = "block";
                    responseMessage.style.background = "rgba(220,53,69,0.85)";
                    responseMessage.style.color = "#fff";
                    responseMessage.innerHTML = "⚠️ Server error, please try again.";
                });
        });
    </script>
@endsection
