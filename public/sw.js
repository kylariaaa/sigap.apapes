const CACHE_NAME = "sigap-v2";
const PRECACHE_URLS = [
    "/",
    "/manifest.json",
    "/logo-192.png",
    "/logo-512.png",
    "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css",
    "https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap",
];

/* ─── INSTALL: Pre-cache semua aset penting ─── */
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

/* ─── FETCH: Cache-first, fallback ke network ─── */
self.addEventListener("fetch", (event) => {
    // Lewati request bukan GET (POST, PUT, dll)
    if (event.request.method !== "GET") return;

    // Lewati request API / backend (hanya cache aset statis & navigasi)
    const url = new URL(event.request.url);
    const isNavigate = event.request.mode === "navigate";
    const isAsset =
        url.pathname.startsWith("/logo") ||
        url.pathname.endsWith(".css") ||
        url.pathname.endsWith(".js") ||
        url.pathname.endsWith(".png") ||
        url.pathname.endsWith(".jpg") ||
        url.pathname.endsWith(".ico");

    if (!isNavigate && !isAsset) return;

    event.respondWith(
        caches.match(event.request).then((cached) => {
            if (cached) return cached;

            return fetch(event.request)
                .then((response) => {
                    // Cache respons segar untuk navigasi
                    if (isNavigate && response.ok) {
                        const clone = response.clone();
                        caches
                            .open(CACHE_NAME)
                            .then((cache) => cache.put(event.request, clone));
                    }
                    return response;
                })
                .catch(() => {
                    // Offline fallback untuk navigasi
                    if (isNavigate) {
                        return caches.match("/");
                    }
                });
        }),
    );
});
