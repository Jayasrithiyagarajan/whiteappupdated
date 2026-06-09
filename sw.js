const CACHE_NAME = 'cims-pwa-cache-v2';
const ASSETS_TO_CACHE = [
  'index.php',
  'assets/img/logo.png',
  'assets/img/favicon.png',
  'assets/img/icon-192x192.png',
  'assets/img/icon-512x512.png'
];

// Install Event
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('Opened cache');
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting();
});

// Activate Event
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            console.log('Clearing old cache');
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }

  const requestUrl = new URL(event.request.url);

  if (requestUrl.origin !== self.location.origin) {
    return;
  }

  const isCssOrJs = event.request.destination === 'style' || event.request.destination === 'script';
  const isPageRequest = event.request.mode === 'navigate' || requestUrl.pathname.endsWith('.php');

  if (isPageRequest) {
    event.respondWith(fetch(event.request));
    return;
  }

  if (isCssOrJs) {
    event.respondWith(
      fetch(event.request).then((fetchResponse) => {
        const responseToCache = fetchResponse.clone();
        caches.open(CACHE_NAME).then((cache) => {
          cache.put(event.request, responseToCache);
        });
        return fetchResponse;
      }).catch(() => caches.match(event.request))
    );
    return;
  }

  event.respondWith(
    caches.match(event.request).then((response) => {
      // Cache hit - return response
      if (response) {
        return response;
      }
      return fetch(event.request).then((fetchResponse) => {
        // Check if we received a valid response
        if (!fetchResponse || fetchResponse.status !== 200 || fetchResponse.type !== 'basic') {
          return fetchResponse;
        }

        // Clone the response to store it in the cache
        const responseToCache = fetchResponse.clone();
        caches.open(CACHE_NAME).then((cache) => {
          cache.put(event.request, responseToCache);
        });

        return fetchResponse;
      });
    }).catch(() => {
        // If fetch fails (offline), and asset not in cache, return a fallback if needed
        // For now, just let it fail gracefully
    })
  );
});
