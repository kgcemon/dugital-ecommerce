const CACHE_NAME = 'codmshop-cache-v1';
const ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/icon.png',
    '/icon.png',
    '/icon.png'
];

self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k=>k!==CACHE_NAME).map(k=>caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', e => {
    e.respondWith(
        caches.match(e.request).then(res => res || fetch(e.request))
    );
});
