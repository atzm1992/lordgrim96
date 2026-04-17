const CACHE = 'gym-cache-v2';
const ASSETS = [
  './',
  './index.html',
  './manifest.webmanifest',
  './icon.svg'
];

self.addEventListener('install', (e) => {
  e.waitUntil(caches.open(CACHE).then((c) => c.addAll(ASSETS)));
  self.skipWaiting();
});

self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (e) => {
  const req = e.request;
  if (req.method !== 'GET') return;

  const url = new URL(req.url);
  if (url.origin !== self.location.origin) return;

  const isHtml = req.mode === 'navigate' ||
                 url.pathname.endsWith('.html') ||
                 url.pathname.endsWith('/');

  // HTML and PHP: network-first so deployments are picked up immediately;
  // fall back to cache when offline.
  if (isHtml || url.pathname.endsWith('.php')) {
    e.respondWith(
      fetch(req).then((resp) => {
        if (resp && resp.ok && isHtml) {
          const clone = resp.clone();
          caches.open(CACHE).then((c) => c.put(req, clone));
        }
        return resp;
      }).catch(() => caches.match(req))
    );
    return;
  }

  // Static assets (icons, manifest, JS, CSS): cache-first w/ background update.
  e.respondWith(
    caches.match(req).then((cached) => {
      const network = fetch(req).then((resp) => {
        if (resp && resp.ok) {
          const clone = resp.clone();
          caches.open(CACHE).then((c) => c.put(req, clone));
        }
        return resp;
      }).catch(() => cached);
      return cached || network;
    })
  );
});
