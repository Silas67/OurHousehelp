// public/js/push-notifications.js

const VAPID_PUBLIC_KEY = document.querySelector('meta[name="vapid-public-key"]').content;

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}

async function subscribeToPush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Web Push not supported in this browser.');
        return;
    }

    try {
        const registration = await navigator.serviceWorker.register('/sw.js');
        await navigator.serviceWorker.ready;

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            console.warn('Push permission denied.');
            updatePushUI(false);
            return;
        }

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
        });

        await fetch('/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(subscription),
        });

        updatePushUI(true);
        console.log('Push subscription successful.');
    } catch (err) {
        console.error('Push subscription failed:', err);
    }
}

async function unsubscribeFromPush() {
    const registration = await navigator.serviceWorker.getRegistration('/sw.js');
    if (!registration) return;

    const subscription = await registration.pushManager.getSubscription();
    if (!subscription) return;

    await fetch('/push/unsubscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ endpoint: subscription.endpoint }),
    });

    await subscription.unsubscribe();
    updatePushUI(false);
}

function updatePushUI(subscribed) {
    const toggle = document.getElementById('push-toggle-radio');
    if (!toggle) return;

    toggle.checked = subscribed;

    toggle.onchange = function () {
        if (toggle.checked) {
            subscribeToPush();
        } else {
            unsubscribeFromPush();
        }
    };
}

// On page load — check current subscription state
(async function init() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    const registration = await navigator.serviceWorker.getRegistration('/sw.js');
    if (!registration) {
        updatePushUI(false);
        return;
    }

    const subscription = await registration.pushManager.getSubscription();
    updatePushUI(!!subscription);
})();
