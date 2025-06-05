const CACHE_VERSION = 'v3';
const CACHE_NAME = 'eymtax-cache-' + CACHE_VERSION;

const urlsToCache = [
  '/',
  '/index.html',
  '/css/style.css',
  '/js/chatbot.js',
  // يمكنك إضافة صفحات أخرى هنا حسب الحاجة
];

const MAX_CACHE_ITEMS = 50;

async function limitCacheSize(cacheName, maxItems) {
  const cache = await caches.open(cacheName);
  const keys = await cache.keys();
  if (keys.length > maxItems) {
    await cache.delete(keys[0]);
    await limitCacheSize(cacheName, maxItems);
  }
}

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then(async cache => {
      for (const url of urlsToCache) {
        try {
          await cache.add(url);
        } catch (err) {
          console.warn('⚠️ فشل تحميل الملف:', url, err);
        }
      }
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
  self.clients.claim();
});

self.addEventListener('fetch', event => {
  const request = event.request;

  if (request.method !== 'GET') return;

  if (
    request.destination === 'image' ||
    request.url.match(/\.(jpg|jpeg|png|gif|svg|webp)$/)
  ) {
    // ⚡ الصور - استراتيجية "Cache, falling back to network"
    event.respondWith(
      caches.match(request).then(cacheResponse => {
        if (cacheResponse) return cacheResponse;
        return fetch(request)
          .then(networkResponse => {
            if (networkResponse.type === 'opaque' || networkResponse.status !== 200) {
              return networkResponse;
            }
            const cloned = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(request, cloned);
              limitCacheSize(CACHE_NAME, MAX_CACHE_ITEMS);
            });
            return networkResponse;
          })
          .catch(() => {
            return new Response(
              `<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect fill="#eee" width="200" height="200"/>
                <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="16" fill="#666">
                  الصورة غير متوفرة
                </text>
              </svg>`, {
                headers: { 'Content-Type': 'image/svg+xml' }
              }
            );
          });
      })
    );
  } else {
    // باقي الطلبات: Network First
    event.respondWith(
      fetch(request)
        .then(networkResponse => {
          const cloned = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, cloned);
            limitCacheSize(CACHE_NAME, MAX_CACHE_ITEMS);
          });
          return networkResponse;
        })
        .catch(() => caches.match(request))
    );
  }
});
