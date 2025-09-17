@extends('user.master')

@section('title', "Deposit")

@section('content')
    <main style="padding: 30px 15px;">
        <div class="panelData" style="max-width: 450px; margin: auto; position: relative;">
            <h2 style="margin-bottom:20px;">Player Information</h2>

            <!-- Response Message -->
            <div id="responseMessage" style="display:none; margin-bottom:15px; padding:15px; border-radius:12px; font-weight:600; text-align:left;"></div>

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

        /* Response box styles */
        #responseMessage {
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: all 0.4s ease;
            line-height:1.5;
        }
        #responseMessage.success {
            background: linear-gradient(145deg, #28a745, #20c997);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        #responseMessage.failed {
            background: linear-gradient(145deg, #dc3545, #ff6b6b);
            color: #fff;
            border: 1px solid rgba(0,0,0,0.2);
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

            // Validation: only numbers + length check
            let playerId = form.player_id.value.trim();
            if (!/^[0-9]+$/.test(playerId)) {
                showMessage("⚠️ Player ID অবশ্যই শুধু সংখ্যা হবে!", "failed");
                return;
            }
            if (playerId.length < 5 || playerId.length > 13) {
                showMessage("⚠️ Player ID কমপক্ষে 5 digit এবং সর্বোচ্চ 13 digit হতে হবে!", "failed");
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

                    if (data.success && data.data) {
                        let d = data.data;
                        // failed_likes check
                        if (d.failed_likes === 0) {
                            showMessage("❌ আপনি আজকে আর লাইক নিতে পারবেন না, আগামিকাল চেষ্টা করুন ধন্যবাদ।", "failed");
                        } else if (typeof d.likes_added !== "undefined") {
                            // Calculate added
                            let added = d.likes_after - d.likes_before;
                            let nextTry = "আগামীকাল চেষ্টা করুন"; // static, could be dynamic based on server

                            showMessage(`
                    ✅ <strong>Success!</strong><br>
                    Name: ${d.name}<br>
                    Region: ${d.region}<br>
                    Level: ${d.level}<br>
                    Likes Before: ${d.likes_before}<br>
                    Likes Added: ${added}<br>
                    Likes After: ${d.likes_after}<br>
                    ${added === 0 ? "⚠️ আজ কোনো লাইক যোগ হয়নি।" : ""}<br>
                    <em>${nextTry}</em>
                `, "success");
                        } else {
                            showMessage("⚠️ Server error, please try again.", "failed");
                        }
                    } else {
                        showMessage("❌ Something went wrong!", "failed");
                    }
                })
                .catch(err => {
                    loader.style.display = "none";
                    clearInterval(countInterval);
                    showMessage("⚠️ Server error, please try again.", "failed");
                });

            function showMessage(msg, type){
                responseMessage.style.display = "block";
                responseMessage.className = "";
                responseMessage.classList.add(type);
                responseMessage.innerHTML = msg;
            }
        });
    </script>
@endsection
