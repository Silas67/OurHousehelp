// public/sw.js
//
// Handles push notifications (as before) and now an offline-capable cache.
//
// CACHE STRATEGY - read this before changing it:
//
//   HTML / navigations  -> network first, cache as fallback.
//        A page must never be served stale after a deploy. If the network
//        fails we fall back to the last good copy, then to /offline.
//
//   Static assets       -> stale-while-revalidate.
//        Vite output carries a content hash in the filename (ui-CF6tM1_e.js),
//        so a new build produces a new URL and cannot collide with an old one.
//
//   Everything else     -> straight to the network, never cached.
//        Anything under /admin, /booking, /documents or /api may be
//        user-specific or private and must not sit in a shared cache.
//
// BUMP CACHE_VERSION on every deploy that changes static assets. Old caches are
// deleted on activate.

const CACHE_VERSION = 'v1';
const STATIC_CACHE = `ohh-static-${CACHE_VERSION}`;
const PAGE_CACHE = `ohh-pages-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline';

// Kept deliberately small: the offline page and the icons it needs. Hashed
// build assets are cached lazily on first use instead of being listed here,
// so this list never goes stale.
const PRECACHE = [OFFLINE_URL, '/icons/icon-192.png'];

// Never cache anything under these prefixes.
const NEVER_CACHE = ['/admin', '/booking', '/documents', '/api', '/login', '/logout', '/credits'];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches
            .open(STATIC_CACHE)
            .then((cache) => cache.addAll(PRECACHE))
            .catch(() => {
                /* a missing precache entry must not block installation */
            })
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter((k) => k.startsWith('ohh-') && k !== STATIC_CACHE && k !== PAGE_CACHE)
                        .map((k) => caches.delete(k))
                )
            )
            .then(() => self.clients.claim())
    );
});

function isStaticAsset(url) {
    return (
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/icons/') ||
        /\.(css|js|woff2?|ttf|png|jpe?g|webp|svg|ico)$/i.test(url.pathname)
    );
}

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') return;

    const url = new URL(request.url);

    // Only handle our own origin.
    if (url.origin !== self.location.origin) return;

    if (NEVER_CACHE.some((prefix) => url.pathname.startsWith(prefix))) return;

    // Navigations: network first.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(PAGE_CACHE).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(() =>
                    caches
                        .match(request)
                        .then((cached) => cached || caches.match(OFFLINE_URL))
                )
        );
        return;
    }

    // Static assets: serve from cache, refresh in the background.
    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                const network = fetch(request)
                    .then((response) => {
                        if (response && response.status === 200) {
                            const copy = response.clone();
                            caches.open(STATIC_CACHE).then((cache) => cache.put(request, copy));
                        }
                        return response;
                    })
                    .catch(() => cached);

                return cached || network;
            })
        );
    }
});

// Allows the page to activate a waiting worker immediately after a deploy.
self.addEventListener('message', (event) => {
    if (event.data === 'SKIP_WAITING') self.skipWaiting();
});

/* ------------------------------------------------------------------
   Push notifications (unchanged behaviour, corrected icon paths)
   ------------------------------------------------------------------ */

self.addEventListener('push', function (event) {
    if (!event.data) return;

    const data = event.data.json();

    const options = {
        body: data.body,
        // Was /images/logo-icon.png, which does not exist in this project, so
        // notifications rendered with no icon.
        icon: data.icon || '/icons/icon-192.png',
        badge: data.badge || '/icons/badge-96.png',
        vibrate: [200, 100, 200],
        data: { url: data.url || '/' },
        actions: [
            { action: 'view', title: 'View Job' },
            { action: 'dismiss', title: 'Dismiss' },
        ],
        requireInteraction: true, // keeps notification visible until tapped
    };

    event.waitUntil(self.registration.showNotification(data.title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.action === 'dismiss') return;

    const url = event.notification.data.url;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            // If app already open, focus it
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            // Otherwise open new tab
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
