/**
 * WAKANDE - Service Worker
 * Version: 1.0.0
 * Last Updated: 2026-02-12
 */

const CACHE_NAME = 'wakande-v1';
const DYNAMIC_CACHE = 'wakande-dynamic-v1';
const OFFLINE_URL = '/offline.html';

// Assets to cache on install
const STATIC_ASSETS = [
  '/',
  '/offline.html',
  '/manifest.json',
  '/css/app.css',
  '/css/dark-mode.css',
  '/js/app.js',
  '/js/pwa.js',
  '/js/theme.js',
  '/icons/icon-72x72.png',
  '/icons/icon-96x96.png',
  '/icons/icon-128x128.png',
  '/icons/icon-144x144.png',
  '/icons/icon-152x152.png',
  '/icons/icon-192x192.png',
  '/icons/icon-384x384.png',
  '/icons/icon-512x512.png',
  'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'
];

// API routes to cache
const API_CACHE_ROUTES = [
  '/api/catalog',
  '/api/categories',
  '/api/schools'
];

// ===== INSTALL EVENT =====
self.addEventListener('install', (event) => {
  console.log('✅ Service Worker installing...');

  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('📦 Caching static assets');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => {
        console.log('✅ Service Worker installed successfully');
        return self.skipWaiting();
      })
      .catch(error => {
        console.error('❌ Failed to cache static assets:', error);
      })
  );
});

// ===== ACTIVATE EVENT =====
self.addEventListener('activate', (event) => {
  console.log('🚀 Service Worker activating...');

  event.waitUntil(
    caches.keys()
      .then(keys => {
        return Promise.all(
          keys
            .filter(key => key !== CACHE_NAME && key !== DYNAMIC_CACHE)
            .map(key => {
              console.log('🗑️ Removing old cache:', key);
              return caches.delete(key);
            })
        );
      })
      .then(() => {
        console.log('✅ Service Worker activated successfully');
        return self.clients.claim();
      })
  );
});

// ===== FETCH EVENT =====
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip cross-origin requests
  if (url.origin !== self.location.origin) {
    if (request.destination === 'font' ||
        request.destination === 'style' ||
        url.href.includes('fonts.googleapis.com') ||
        url.href.includes('cdn.jsdelivr.net')) {
      event.respondWith(cacheFirst(event.request));
    }
    return;
  }

  // Handle API requests
  if (url.pathname.startsWith('/api/')) {
    if (API_CACHE_ROUTES.includes(url.pathname)) {
      event.respondWith(networkWithCacheFallback(request));
    } else {
      event.respondWith(networkFirst(request));
    }
    return;
  }

  // Handle page navigation
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then(response => {
          const copy = response.clone();
          caches.open(DYNAMIC_CACHE).then(cache => {
            cache.put(request, copy);
          });
          return response;
        })
        .catch(() => caches.match(OFFLINE_URL))
    );
    return;
  }

  // Handle static assets
  if (STATIC_ASSETS.includes(url.pathname)) {
    event.respondWith(cacheFirst(request));
  } else {
    event.respondWith(networkWithCacheFallback(request));
  }
});

// ===== CACHE STRATEGIES =====

// Cache First - for static assets
async function cacheFirst(request) {
  const cachedResponse = await caches.match(request);
  if (cachedResponse) {
    return cachedResponse;
  }

  try {
    const networkResponse = await fetch(request);
    const cache = await caches.open(DYNAMIC_CACHE);
    cache.put(request, networkResponse.clone());
    return networkResponse;
  } catch (error) {
    console.error('❌ Cache first failed:', error);

    // Return offline image for images
    if (request.destination === 'image') {
      return caches.match('/icons/icon-192x192.png');
    }

    return new Response('Offline', { status: 503 });
  }
}

// Network First - for dynamic content
async function networkFirst(request) {
  try {
    const networkResponse = await fetch(request);
    const cache = await caches.open(DYNAMIC_CACHE);
    cache.put(request, networkResponse.clone());
    return networkResponse;
  } catch (error) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }

    // Return offline page for navigation
    if (request.mode === 'navigate') {
      return caches.match(OFFLINE_URL);
    }

    return new Response('Offline', { status: 503 });
  }
}

// Network with Cache Fallback
async function networkWithCacheFallback(request) {
  // WAJIB: Bypass Cache untuk metode selain GET (POST, PUT, PATCH, DELETE)
  if (request.method !== 'GET') {
    return fetch(request);
  }

  try {
    const networkResponse = await fetch(request);

    // Validasi response sebelum masuk cache
    if (networkResponse && networkResponse.status === 200) {
      const cache = await caches.open(DYNAMIC_CACHE);
      // Hanya simpan jika request adalah GET
      cache.put(request, networkResponse.clone());
    }

    return networkResponse;
  } catch (error) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) return cachedResponse;

    // Jika offline dan bukan GET, berikan respon JSON agar tidak error SyntaxError
    return new Response(JSON.stringify({
      error: 'offline',
      message: 'Koneksi terputus, tidak bisa mengirim data.'
    }), {
      status: 503,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}

// Stale While Revalidate
async function staleWhileRevalidate(request) {
  const cachedResponse = await caches.match(request);

  const networkPromise = fetch(request)
    .then(response => {
      const cache = caches.open(DYNAMIC_CACHE);
      cache.then(c => c.put(request, response.clone()));
      return response;
    })
    .catch(error => console.error('❌ Stale while revalidate failed:', error));

  return cachedResponse || networkPromise;
}

// ===== PUSH NOTIFICATIONS =====
self.addEventListener('push', (event) => {
  if (!event.data) return;

  try {
    const data = event.data.json();

    const options = {
      body: data.body || 'Ada update baru dari WAKANDE',
      icon: '/icons/icon-192x192.png',
      badge: '/icons/icon-72x72.png',
      vibrate: [200, 100, 200],
      data: {
        url: data.url || '/',
        id: data.id || Date.now()
      },
      actions: [
        {
          action: 'open',
          title: 'Buka'
        },
        {
          action: 'close',
          title: 'Tutup'
        }
      ],
      tag: data.tag || 'notification',
      renotify: true,
      requireInteraction: true,
      silent: false
    };

    event.waitUntil(
      self.registration.showNotification(data.title || 'WAKANDE', options)
    );
  } catch (error) {
    console.error('❌ Failed to show notification:', error);
  }
});

// ===== NOTIFICATION CLICK =====
self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  if (event.action === 'close') {
    return;
  }

  const urlToOpen = event.notification.data?.url || '/';

  event.waitUntil(
    clients.matchAll({
      type: 'window',
      includeUncontrolled: true
    }).then(clientList => {
      // Check if there's already a window/tab open
      for (const client of clientList) {
        if (client.url === urlToOpen && 'focus' in client) {
          return client.focus();
        }
      }

      // If not, open a new window/tab
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});

// ===== BACKGROUND SYNC =====
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-transactions') {
    event.waitUntil(syncTransactions());
  }
});

async function syncTransactions() {
  try {
    const cache = await caches.open(DYNAMIC_CACHE);
    const offlineQueue = await getOfflineQueue();

    for (const action of offlineQueue) {
      try {
        const response = await fetch(action.url, {
          method: action.method,
          headers: action.headers,
          body: JSON.stringify(action.data)
        });

        if (response.ok) {
          await removeFromQueue(action.id);

          // Notify client that sync was successful
          const clients = await self.clients.matchAll();
          clients.forEach(client => {
            client.postMessage({
              type: 'sync-complete',
              actionId: action.id
            });
          });
        }
      } catch (error) {
        console.error('❌ Failed to sync transaction:', error);
      }
    }
  } catch (error) {
    console.error('❌ Failed to sync transactions:', error);
  }
}

// ===== HELPER FUNCTIONS =====
async function getOfflineQueue() {
  const cache = await caches.open(DYNAMIC_CACHE);
  const response = await cache.match('/offline-queue');
  if (response) {
    return await response.json();
  }
  return [];
}

async function removeFromQueue(actionId) {
  const queue = await getOfflineQueue();
  const updatedQueue = queue.filter(item => item.id !== actionId);

  const cache = await caches.open(DYNAMIC_CACHE);
  await cache.put('/offline-queue', new Response(JSON.stringify(updatedQueue)));
}

// ===== MESSAGE HANDLING =====
self.addEventListener('message', (event) => {
  if (event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }

  if (event.data.type === 'CLEAR_CACHE') {
    caches.keys().then(keys => {
      keys.forEach(key => {
        if (key !== CACHE_NAME && key !== DYNAMIC_CACHE) {
          caches.delete(key);
        }
      });
    });
  }
});

// ===== PERIODIC BACKGROUND SYNC =====
if ('periodicSync' in self.registration) {
  self.addEventListener('periodicsync', (event) => {
    if (event.tag === 'update-catalog') {
      event.waitUntil(updateCatalogCache());
    }
  });
}

async function updateCatalogCache() {
  const cache = await caches.open(DYNAMIC_CACHE);
  const catalogResponse = await fetch('/api/catalog');
  await cache.put('/api/catalog', catalogResponse.clone());
  return catalogResponse;
}

// ===== OFFLINE ANALYTICS =====
self.addEventListener('sync', (event) => {
  if (event.tag === 'analytics') {
    event.waitUntil(sendAnalytics());
  }
});

async function sendAnalytics() {
  const cache = await caches.open(DYNAMIC_CACHE);
  const response = await cache.match('/analytics-queue');

  if (response) {
    const queue = await response.json();

    for (const event of queue) {
      try {
        await fetch('/api/analytics', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(event)
        });
      } catch (error) {
        console.error('❌ Failed to send analytics:', error);
      }
    }

    await cache.delete('/analytics-queue');
  }
}

console.log('✅ Service Worker loaded successfully');
