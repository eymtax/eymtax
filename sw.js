const CACHE_VERSION = 'v1.8';
const CACHE_NAME = 'eymtax-cache-' + CACHE_VERSION;

const urlsToCache = [
  '/',
  '/index.html',
  '/about.html',
  '/contact.html',
  '/service.html',
  '/team.html',
  '/css/style.css',
  '/js/chatbot.js',
  // لا تضف ملفات من CDN هنا
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
          await limitCacheSize(CACHE_NAME, MAX_CACHE_ITEMS);
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

  if (request.method !== 'GET') {
    // لا نتعامل إلا مع طلبات GET
    return;
  }

  if (request.destination === 'document') {
    // Network First للصفحات HTML
    event.respondWith(
      fetch(request)
        .then(networkResponse => {
          const clonedResponse = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, clonedResponse);
            limitCacheSize(CACHE_NAME, MAX_CACHE_ITEMS);
          });
          return networkResponse;
        })
        .catch(() => caches.match(request))
    );
  } else if (request.destination === 'image' || request.destination === 'script' || request.destination === 'style') {
    // Cache First للصور، السكريبت، والستايل
    event.respondWith(
      caches.match(request).then(cacheResponse => {
        if (cacheResponse) return cacheResponse;

        return fetch(request).then(networkResponse => {
          // لا نخزن opaque responses (CDN مثلا)
          if (networkResponse.type === 'opaque') return networkResponse;

          const clonedResponse = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, clonedResponse);
            limitCacheSize(CACHE_NAME, MAX_CACHE_ITEMS);
          });
          return networkResponse;
        }).catch(() => {
          if (request.destination === 'image') {
            return new Response(
              `<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect fill="#ccc" width="200" height="200"/>
                <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="16">الصورة غير متوفرة</text>
              </svg>`, {
                headers: { 'Content-Type': 'image/svg+xml' }
            });
          }
          return new Response('الصفحة غير متوفرة حالياً.', { status: 404, statusText: 'Not Found' });
        });
      })
    );
  } else {
    // طلبات أخرى: حاول أولاً الشبكة ثم الكاش
    event.respondWith(
      fetch(request).catch(() => caches.match(request))
    );
  }
});
