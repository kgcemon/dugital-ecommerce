const CACHE_NAME = 'codmshop-cache-v1';
const ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/images/logo.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-512x512.png'
];

// Install SW
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
    self.skipWaiting();
});

// Activate SW
self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(key => key !== CACHE_NAME)
                .map(key => caches.delete(key)))
        )
    );
    self.clients.claim();
});

// Fetch cached assets
self.addEventListener('fetch', e => {
    e.respondWith(
        caches.match(e.request).then(res => res || fetch(e.request))
    );
});
