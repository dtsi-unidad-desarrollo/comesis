// Service Worker para COMESIS - Soporte de Instalación de Aplicación de Escritorio y Móvil
const CACHE_NAME = 'comesis-cache-v1';

self.addEventListener('install', (event) => {
    // Activar inmediatamente el nuevo service worker
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    // Tomar control inmediato de todas las pestañas abiertas
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    // Estrategia Network-First: Siempre prioriza peticiones vivas del servidor
    // sin interferir en tokens CSRF, sesiones de Laravel o transacciones
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});
