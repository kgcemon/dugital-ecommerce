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
            <p style="font-size: 0.9rem; margin-bottom: 15px;">আমাদের App ডাউনলোড করুন</p>
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
