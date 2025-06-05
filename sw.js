const CACHE_NAME = 'eymtax-cache-' + Date.now();

const urlsToCache = [
  '/',
  '/index.html',
  '/css/style.css',
  '/js/chatbot.js',
  '/contact.html',
  // لا تضع روابط خارجية من CDN هنا
];

self.addEventListener('install', event => {
  self.skipWaiting(); // يجعل Service Worker مفعّل فورًا
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return Promise.all(
        urlsToCache.map(url => {
          return cache.add(url).catch(err => {
            console.warn('⚠️ فشل تحميل الملف:', url, err);
          });
        })
      );
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
  self.clients.claim(); // يضمن تطبيق النسخة الجديدة مباشرة
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request).catch(err => {
        console.warn('❌ فشل تحميل:', event.request.url);
        return new Response("الصفحة غير متوفرة حالياً.", {
          status: 404,
          statusText: 'Not Found'
        });
      });
    })
  );
});
