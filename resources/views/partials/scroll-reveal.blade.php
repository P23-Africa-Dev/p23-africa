<style>
    .scroll-reveal {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity 0.75s ease, transform 0.75s ease;
    }

    .scroll-reveal.is-inview {
        opacity: 1;
        transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .scroll-reveal {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }
</style>
<script>
    (function() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        var root = document.querySelector('main');
        if (!root) {
            return;
        }

        var nodes = Array.prototype.slice.call(
            root.querySelectorAll('section, article, .animate-on-scroll, .reveal-section')
        );

        nodes = nodes.filter(function(el) {
            if (el.closest('nav, .navbar, .navbar-full, .p23-navbar-wrapper, .modal')) {
                return false;
            }
            if (el.hasAttribute('data-no-reveal')) {
                return false;
            }
            if (el.offsetHeight < 20 && el.offsetWidth < 20) {
                return false;
            }
            return !nodes.some(function(other) {
                return other !== el && el.contains(other);
            });
        });

        if (!nodes.length) {
            nodes = Array.prototype.filter.call(root.children, function(el) {
                return el.nodeType === 1 &&
                    !el.matches('script, style, nav, .navbar') &&
                    el.offsetHeight > 48;
            });
        }

        if (!nodes.length) {
            return;
        }

        nodes.forEach(function(el) {
            el.classList.add('scroll-reveal');
        });

        var pending = nodes.slice();
        var observer = null;
        var armed = false;

        function show(el, index) {
            el.style.transitionDelay = Math.min(index, 6) * 90 + 'ms';
            el.classList.add('is-inview');
            if (observer) {
                observer.unobserve(el);
            }
        }

        function inView(el) {
            var rect = el.getBoundingClientRect();
            return rect.top < window.innerHeight && rect.bottom > 24;
        }

        function revealVisible() {
            var batch = 0;
            pending = pending.filter(function(el) {
                if (!inView(el)) {
                    return true;
                }
                show(el, batch);
                batch += 1;
                return false;
            });
        }

        if (!('IntersectionObserver' in window)) {
            nodes.forEach(function(el, index) {
                show(el, index);
            });
            return;
        }

        observer = new IntersectionObserver(function(entries) {
            if (!armed) {
                return;
            }
            entries.forEach(function(entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                var index = pending.indexOf(entry.target);
                if (index === -1) {
                    return;
                }
                show(entry.target, 0);
                pending.splice(index, 1);
            });
        }, {
            threshold: 0,
            rootMargin: '0px 0px -10% 0px'
        });

        nodes.forEach(function(el) {
            observer.observe(el);
        });

        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                revealVisible();
                armed = true;
                pending.slice().forEach(function(el) {
                    observer.unobserve(el);
                    observer.observe(el);
                });
            });
        });

        window.addEventListener('load', revealVisible);
    })();
</script>
