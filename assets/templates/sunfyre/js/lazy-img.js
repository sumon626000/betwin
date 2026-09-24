/**
 * Lazy-load home images via data-src + IntersectionObserver.
 * Native loading="lazy" is kept as fallback; this upgrades below-fold rows.
 */
(function () {
    if (window.B369Lazy) return;

    function hydrate(img) {
        if (!img || img.dataset.lazyDone === '1') return;
        var src = img.getAttribute('data-src');
        if (!src) return;
        img.src = src;
        img.removeAttribute('data-src');
        img.dataset.lazyDone = '1';
    }

    function observe() {
        var imgs = document.querySelectorAll('img[data-src]');
        if (!imgs.length) return;

        if (!('IntersectionObserver' in window)) {
            imgs.forEach(hydrate);
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                hydrate(entry.target);
                io.unobserve(entry.target);
            });
        }, { rootMargin: '180px 0px', threshold: 0.01 });

        imgs.forEach(function (img) { io.observe(img); });
    }

    window.B369Lazy = { hydrate: hydrate, observe: observe };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', observe);
    } else {
        observe();
    }
})();
