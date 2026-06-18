// Install Service Worker
self.addEventListener('install', (event) => {
    console.log('Service Worker ter-install!');
    self.skipWaiting();
});

// Activate Service Worker
self.addEventListener('activate', (event) => {
    console.log('Service Worker aktif!');
});

// Fetch data (Biarkan request berjalan normal ke server karena kita pakai PHP/CI3)
self.addEventListener('fetch', (event) => {
    event.respondWith(fetch(event.request));
});