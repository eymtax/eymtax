const CACHE_VERSION = 'v1.4'; // غيّر هذا الرقم يدويًا عند تحديث الملفات
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
