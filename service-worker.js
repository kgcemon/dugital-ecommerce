const cacheName = "gaming-shop-cache-v1";
const assets = [
    "/",
    "/css/app.css",
    "/js/app.js",
    "/images/logo.png",
    "/images/icons/icon-192x192.png",
    "/images/icons/icon-512x512.png"
];

// Install service worker
self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(cacheName).then(cache => cache.addAll(assets))
    );
});

// Activate service worker
self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(key => key !== cacheName)
                .map(key => caches.delete(key)))
        )
    );
});

// Fetch cached assets
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request).then(response => response || fetch(event.request))
    );
});
