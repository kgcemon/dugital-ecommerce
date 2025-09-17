@extends('user.master')

@section('title', "Deposit")

@section('content')
    <main style="padding: 30px 15px;">
        <div class="panelData" style="max-width: 450px; margin: auto; position: relative;">
            <h2 style="margin-bottom:20px; text-align:center;">Player Information</h2>

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
                <div style="margin-bottom: 15px; text-align: left;">
                    <label for="player_id" style="font-weight:600;">Player ID</label>
                    <input type="text" id="player_id" name="player_id" class="form-control"
                           placeholder="Enter your Player ID" required
                           style="width:100%;padding:10px;border-radius:8px;border:none;outline:none;margin-top:5px;">
                </div>

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
            padding: 25px;
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: #fff;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5), 0 0 20px rgba(255,255,255,0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            font-family: 'Arial', sans-serif;
            position: relative;
            overflow: hidden;
            animation: glow 2s ease-in-out infinite alternate;
        }
        #responseMessage .card.failed {
            background: linear-gradient(135deg, #ff4e50, #f9d423);
        }
        #responseMessage .card .site-brand {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 20px;
            text-align: center;
            color: #ffe600; /* Highlight Codmshop.Com */
            text-shadow: 0 0 10px #ff00ff, 0 0 20px #ff00ff;
            letter-spacing: 1px;
        }
        #responseMessage .card .player-info {
            margin-bottom: 10px;
            font-size: 15px;
        }
        #responseMessage .card .likes {
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
        }
        #responseMessage .card .footer {
            margin-top: 15px;
            font-size: 13px;
            opacity: 0.85;
            text-align: center;
        }

        @keyframes glow {
            0% { box-shadow: 0 0 20px rgba(255, 255, 255, 0.2), 0 0 40px rgba(255, 255, 255, 0.1); }
            100% { box-shadow: 0 0 35px rgba(255, 255, 255, 0.5), 0 0 60px rgba(255, 255, 255, 0.3); }
        }

        /* Sparkles */
        #responseMessage .sparkle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #fff;
            border-radius: 50%;
            animation: sparkleAnim 2s linear infinite;
            opacity: 0.8;
            pointer-events: none;
        }
        @keyframes sparkleAnim {
            0% { transform: translate(0,0) scale(1); opacity:0.8;}
            50% { transform: translate(10px, -10px) scale(1.5); opacity:1;}
            100% { transform: translate(0,0) scale(1); opacity:0.8;}
        }

        /* Share/Download buttons */
        .share-download {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .share-download button {
            padding: 10px 18px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .share-download .download {
            background: linear-gradient(135deg,#00f2fe,#4facfe);
            color: #fff;
            box-shadow: 0 5px 15px rgba(0,244,254,0.4);
        }

        .share-download .download:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,244,254,0.6);
        }

        .share-download .share {
            background: linear-gradient(135deg,#f093fb,#f5576c);
            color: #fff;
            box-shadow: 0 5px 15px rgba(240,147,251,0.4);
        }

        .share-download .share:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(240,147,251,0.6);
        }
    </style>

    <!-- Include html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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

                        if (d.failed_likes === 0) {
                            showMessage("আপনি আজকে আর লাইক নিতে পারবেন না, আগামিকাল চেষ্টা করুন ধন্যবাদ।", true);
                        }
                        else if (typeof d.likes_added !== "undefined") {
                            let added = d.likes_after - d.likes_before;

                            let html = `
                <div class="card" id="successCard">
                    <span class="site-brand">Codmshop.Com</span>
                    <div class="player-info"><strong>Name:</strong> ${d.name}</div>
                    <div class="player-info"><strong>Region:</strong> ${d.region}</div>
                    <div class="player-info"><strong>Level:</strong> ${d.level}</div>
                    <div class="likes"><strong>Likes Before:</strong> ${d.likes_before}</div>
                    <div class="likes"><strong>Likes Added:</strong> ${added}</div>
                    <div class="likes"><strong>Likes After:</strong> ${d.likes_after}</div>
                    <div class="footer">${added === 0 ? "⚠️ আজ কোনো লাইক যোগ হয়নি।" : ""}</div>
                    <div class="share-download">
                        <button class="download">Download</button>
                        <button class="share">Share</button>
                    </div>
                </div>
                `;
                            showMessage(html, false);

                            // Add sparkles
                            for (let i = 0; i < 12; i++) {
                                let sparkle = document.createElement('div');
                                sparkle.classList.add('sparkle');
                                sparkle.style.top = Math.random() * 90 + '%';
                                sparkle.style.left = Math.random() * 90 + '%';
                                document.getElementById('successCard').appendChild(sparkle);
                            }

                            // Download
                            document.querySelector('#successCard .download').addEventListener('click', () => {
                                html2canvas(document.getElementById('successCard')).then(canvas => {
                                    let link = document.createElement('a');
                                    link.download = `player_${d.player_id}.png`;
                                    link.href = canvas.toDataURL("image/png");
                                    link.click();
                                });
                            });

                            // Share
                            document.querySelector('#successCard .share').addEventListener('click', () => {
                                if (navigator.share) {
                                    html2canvas(document.getElementById('successCard')).then(canvas => {
                                        canvas.toBlob(blob => {
                                            const file = new File([blob], `player_${d.player_id}.png`, {type: 'image/png'});
                                            navigator.share({
                                                files: [file],
                                                title: 'My Player Info',
                                                text: 'Check out my player stats!',
                                            });
                                        });
                                    });
                                } else {
                                    alert("Your browser does not support sharing.");
                                }
                            });

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
