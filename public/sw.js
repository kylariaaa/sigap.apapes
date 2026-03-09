const CACHE_NAME = "sigap-v3";

// Hanya cache aset statis yang benar-benar aman di-cache
// JANGAN masukkan URL navigasi Laravel (/, /lapor, dll) karena mengandung CSRF token & session
const PRECACHE_URLS = ["/logo-192.png", "/logo-512.png", "/manifest.json"];

/* ─── INSTALL ─── */
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_URLS);
        }),
    );
    self.skipWaiting();
});

/* ─── ACTIVATE: Hapus cache lama ─── */
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) =>
                Promise.all(
                    cacheNames
                        .filter((name) => name !== CACHE_NAME)
                        .map((name) => caches.delete(name)),
                ),
            ),
    );
    self.clients.claim();
});

/* ─── FETCH: Stale-while-revalidate hanya untuk aset statis ─── */
self.addEventListener("fetch", (event) => {
    // Lewati semua request non-GET
    if (event.request.method !== "GET") return;

    const url = new URL(event.request.url);

    // ── 1. Navigasi (halaman HTML Laravel) → selalu ke network, TIDAK di-cache
    //       Ini penting agar CSRF token & session tidak pernah stale
    if (event.request.mode === "navigate") return;

    // ── 2. Request ke domain lain selain aset CDN tertentu → lewati
    const isSameOrigin = url.origin === self.location.origin;
    const isTrustedCDN =
        url.hostname === "cdn.jsdelivr.net" ||
        url.hostname === "fonts.googleapis.com" ||
        url.hostname === "fonts.gstatic.com" ||
        url.hostname === "unpkg.com";

    if (!isSameOrigin && !isTrustedCDN) return;

    // ── 3. Aset statis di origin sendiri
    const isStaticAsset =
        url.pathname.startsWith("/build/") || // Vite build output
        url.pathname.startsWith("/logo") ||
        url.pathname.endsWith(".css") ||
        url.pathname.endsWith(".js") ||
        url.pathname.endsWith(".png") ||
        url.pathname.endsWith(".jpg") ||
        url.pathname.endsWith(".jpeg") ||
        url.pathname.endsWith(".webp") ||
        url.pathname.endsWith(".ico") ||
        url.pathname.endsWith(".woff2") ||
        url.pathname === "/manifest.json";

    if (!isStaticAsset && !isTrustedCDN) return;

    // Cache-first dengan network fallback
    event.respondWith(
        caches.match(event.request).then((cached) => {
            const networkFetch = fetch(event.request).then((response) => {
                if (response.ok) {
                    const clone = response.clone();
                    caches
                        .open(CACHE_NAME)
                        .then((cache) => cache.put(event.request, clone));
                }
                return response;
            });
            return cached || networkFetch;
        }),
    );
});
