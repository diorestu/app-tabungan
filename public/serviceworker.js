var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
    '/images/logo.png',
    '/favicon.png',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache).catch(err => {
                    console.log('PWA cache addAll non-fatal warning:', err);
                });
            })
    );
});

// Clear old cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache (only intercept GET requests to avoid breaking Livewire/API POST calls)
self.addEventListener("fetch", event => {
    if (event.request.method !== 'GET') {
        return;
    }

    // Do not cache livewire requests
    const url = new URL(event.request.url);
    if (url.pathname.startsWith('/livewire')) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                if (event.request.mode === 'navigate') {
                    return caches.match('/offline') || fetch(event.request);
                }
            })
    );
});
