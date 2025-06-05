const CACHE_VERSION = 'v1.6'; // غيّر الرقم عند كل تحديث
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

// حد أقصى للعناصر داخل الكاش
const MAX_CACHE_ITEMS = 30;

// دالة لتقليص حجم الكاش عند الامتلاء
async function limitCacheSize(cacheName, maxItems) {
  const cache = await caches.open(cacheName);
  const keys = await cache.keys();
  if (keys.length > maxItems) {
    await cache.delete(keys[0]);
    await limitCacheSize(cacheName, maxItems);
  }
}

self.addEventListener('install', event => {
  self.skipWaiting(); // يجعل Service Worker مفعّل فورًا
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
  const { request } = event;

  if (request.destination === 'document') {
    // Network First للصفحات
    event.respondWith(
      fetch(request)
        .then(res => {
          const resClone = res.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, resClone);
            limitCacheSize(CACHE_NAME, MAX_CACHE_ITEMS);
          });
          return res;
        })
        .catch(() => caches.match(request))
    );
  } else {
    // Cache First لباقي الملفات (صور، CSS، JS...)
    event.respondWith(
      caches.match(request).then(cacheRes => {
        return cacheRes || fetch(request).then(fetchRes => {
          if (fetchRes.type === 'opaque') return fetchRes;

          const clonedRes = fetchRes.clone();
          caches.open(CACHE_NAME).then(cache => {
            cache.put(request, clonedRes);
            limitCacheSize(CACHE_NAME, MAX_CACHE_ITEMS);
          });

          return fetchRes;
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

          return new Response("الصفحة غير متوفرة حالياً.", {
            status: 404,
            statusText: 'Not Found'
          });
        });
      })
    );
  }
});
