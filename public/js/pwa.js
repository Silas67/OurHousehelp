// public/js/pwa.js
//
// Registers the service worker for every visitor.
//
// Previously registration lived only in push-notifications.js, which is loaded
// from the applicant sidebar - so staff got a service worker and clients and
// admins never did. Push subscription still happens there (it needs the VAPID
// key and user permission); this file only handles registration and updates,
// which everyone benefits from.

(function () {
    if (!('serviceWorker' in navigator)) return;

    window.addEventListener('load', function () {
        navigator.serviceWorker
            .register('/sw.js')
            .then(function (registration) {
                // A new worker waits until every tab is closed. Prompting it to
                // activate means a deploy reaches users on their next
                // navigation rather than days later.
                if (registration.waiting) {
                    registration.waiting.postMessage('SKIP_WAITING');
                }

                registration.addEventListener('updatefound', function () {
                    var incoming = registration.installing;
                    if (!incoming) return;

                    incoming.addEventListener('statechange', function () {
                        if (incoming.state === 'installed' && navigator.serviceWorker.controller) {
                            incoming.postMessage('SKIP_WAITING');
                        }
                    });
                });
            })
            .catch(function (error) {
                console.warn('Service worker registration failed:', error);
            });

        // Reload once when a new worker takes over, so the user is not left on
        // a half-old page after an update.
        var refreshing = false;
        navigator.serviceWorker.addEventListener('controllerchange', function () {
            if (refreshing) return;
            refreshing = true;
            window.location.reload();
        });
    });
})();
