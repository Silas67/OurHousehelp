// public/sw.js

self.addEventListener('push', function (event) {
    if (!event.data) return;

    const data = event.data.json();

    const options = {
        body: data.body,
        icon: data.icon || '/images/logo-icon.png',
        badge: data.badge || '/images/logo-icon.png',
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
