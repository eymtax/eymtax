const CACHE_NAME = 'eymtax-cache-' + Date.now();

const urlsToCache = [
    '/',
    '/index.html',
    '/css/style.css',
    '/js/chatbot.js',
'/contact.html',
    // لا تضف ملفات من CDN هنا
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return Promise.all(
                urlsToCache.map(url => {
                    return cache.add(url).catch(err => {
                        console.warn('Failed to cache', url, err);
                    });
                })
            );
        })
    );
});


self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames =>
            Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            )
        )
    );
});
