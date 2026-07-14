const CACHE_NAME = 'prp-shop-v2';

const STATIC_FILES = [
    '/css/style.css',
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_FILES);
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            )
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // فقط GET
    if (request.method !== 'GET') return;

    // صفحات HTML همیشه از شبکه
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match(request))
        );
        return;
    }

    // این مسیرها هرگز کش نشوند
    const noCachePaths = ['/api', '/order', '/get-cities'];
    if (noCachePaths.some(path => url.pathname.startsWith(path))) {
        return;
    }

    // تصاویر، css، js، فونت‌ها — cache first
    if (
        request.destination === 'image' ||
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'font'
    ) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;

                return fetch(request).then((response) => {
                    if (!response || response.status !== 200) return response;

                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(request, clone);
                    });
                    return response;
                });
            })
        );
    }
});
