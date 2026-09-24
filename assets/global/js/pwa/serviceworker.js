var staticCacheName = "b369-pwa-v3";
var IMG_HOSTS = [
    "ossimg.91admin123admin.com",
    "ossimg.dkwinpicture.com",
    "huidu-bucket.s3.ap-southeast-1.amazonaws.com"
];

self.addEventListener("install", function (e) {
    self.skipWaiting();
    e.waitUntil(
        caches.open(staticCacheName).then(function (cache) {
            return cache.addAll(["/"]).catch(function () {});
        })
    );
});

self.addEventListener("activate", function (e) {
    e.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys.filter(function (k) { return k !== staticCacheName; }).map(function (k) {
                    return caches.delete(k);
                })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

function isImageRequest(url) {
    if (/\.(png|jpe?g|webp|gif|svg|ico)(\?|$)/i.test(url.pathname)) return true;
    return IMG_HOSTS.indexOf(url.hostname) !== -1;
}

function isStaticAsset(url) {
    return /\.(css|js|woff2?|ttf|eot)(\?|$)/i.test(url.pathname)
        || url.pathname.indexOf("/assets/") === 0
        || url.pathname.indexOf("/games/") === 0;
}

self.addEventListener("fetch", function (event) {
    if (event.request.method !== "GET") return;

    var url;
    try {
        url = new URL(event.request.url);
    } catch (e) {
        return;
    }

    // Game logos + CDN images: cache-first (fast repeat visits)
    if (isImageRequest(url)) {
        event.respondWith(
            caches.open(staticCacheName).then(function (cache) {
                return cache.match(event.request).then(function (hit) {
                    if (hit) return hit;
                    return fetch(event.request).then(function (res) {
                        if (res && res.ok) {
                            cache.put(event.request, res.clone());
                        }
                        return res;
                    }).catch(function () {
                        return hit || Response.error();
                    });
                });
            })
        );
        return;
    }

    // Local CSS/JS/game JSON: stale-while-revalidate
    if (url.origin === self.location.origin && isStaticAsset(url)) {
        event.respondWith(
            caches.open(staticCacheName).then(function (cache) {
                return cache.match(event.request).then(function (hit) {
                    var net = fetch(event.request).then(function (res) {
                        if (res && res.ok) {
                            cache.put(event.request, res.clone());
                        }
                        return res;
                    }).catch(function () {
                        return hit;
                    });
                    return hit || net;
                });
            })
        );
        return;
    }

    // Navigation / other: network with offline fallback
    event.respondWith(
        fetch(event.request).catch(function () {
            return caches.match(event.request);
        })
    );
});
