<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    @php
        $firstHeroSlide = ($heroSlides ?? collect())->first();
        $firstHeroPreloadSrcset = $firstHeroSlide
            ? ($firstHeroSlide->image_avif_srcset ?: $firstHeroSlide->image_srcset)
            : null;
    @endphp
    @if($firstHeroSlide)
        <link rel="preload"
              as="image"
              href="{{ asset($firstHeroSlide->image_avif_url ?: $firstHeroSlide->image_url) }}"
              @if($firstHeroPreloadSrcset) imagesrcset="{{ $firstHeroPreloadSrcset }}" @endif
              imagesizes="{{ $firstHeroSlide->image_sizes }}"
              type="{{ $firstHeroSlide->image_avif_url ? 'image/avif' : 'image/webp' }}"
              fetchpriority="high">
    @endif
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}?v={{ filemtime(public_path('vendor/bootstrap/bootstrap.min.css')) }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}?v={{ filemtime(public_path('vendor/fontawesome/css/all.min.css')) }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}?v={{ filemtime(public_path('css/chatbot.css')) }}">
    @stack('styles')
</head>
<body>
    @php
        $loaderIsActive = (bool) (($home ?? null)?->loader_is_active ?? true);
        $loaderText = (($home ?? null)?->exists ?? false)
            ? trim((string) $home->loader_text)
            : 'Loading...';
        $cursorIsActive = (bool) (($home ?? null)?->cursor_is_active ?? true);
        $cursorColor = strtolower((string) (($home ?? null)?->cursor_color ?? '#ffcc00'));
        $cursorColor = preg_match('/^#[0-9a-f]{6}$/', $cursorColor) ? $cursorColor : '#ffcc00';
    @endphp
    @if($cursorIsActive)
        <div class="site-cursor" style="--site-cursor-color: {{ $cursorColor }};" aria-hidden="true"></div>
    @endif
    @if($loaderIsActive)
        <div id="pageLoader" class="page-loader page-loader--hidden" role="status" aria-live="polite" aria-label="Loading page">
            <div class="page-loader__content">
                <div class="page-loader__spinner">
                    <span class="page-loader__ring" aria-hidden="true"></span>
                    @if(($home ?? null)?->loader_logo_url)
                        <img src="{{ asset($home->loader_logo_url) }}" alt="" class="page-loader__logo">
                    @else
                        <i class="fa-solid fa-graduation-cap page-loader__fallback-icon" aria-hidden="true"></i>
                    @endif
                </div>
                @if($loaderText !== '')
                    <div class="page-loader__text">{{ $loaderText }}</div>
                @endif
            </div>
        </div>
    @endif

    @yield('content')

    @include('frontend.partials.chatbot')

    <div class="gallery-lightbox" id="globalImageLightbox" aria-hidden="true">
        <button type="button" class="gallery-lightbox__backdrop" data-global-image-close aria-label="Close image preview"></button>
        <div class="gallery-lightbox__stage" role="dialog" aria-modal="true" aria-label="Image preview">
            <div class="gallery-lightbox__toolbar">
                <span class="gallery-lightbox__counter" id="globalImageCounter"></span>
                <div class="gallery-lightbox__actions">
                    <a href="#" class="gallery-lightbox__button" id="globalImageDownload" download aria-label="Download current image">
                        <i class="fa-solid fa-download"></i>
                    </a>
                    <button type="button" class="gallery-lightbox__button" data-global-image-close aria-label="Close image preview">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--prev" data-global-image-prev aria-label="Previous image">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <img src="" alt="" class="gallery-lightbox__image" id="globalImageLightboxImage">
            <button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--next" data-global-image-next aria-label="Next image">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}?v={{ filemtime(public_path('vendor/bootstrap/bootstrap.bundle.min.js')) }}"></script>
    <script src="{{ asset('js/chatbot.js') }}?v={{ filemtime(public_path('js/chatbot.js')) }}" defer></script>
    @stack('scripts')
    <script>
        (() => {
            const cursor = document.querySelector('.site-cursor');
            const canUseCursor = window.matchMedia(
                '(hover: hover) and (pointer: fine) and (prefers-reduced-motion: no-preference)'
            );

            if (!cursor || !canUseCursor.matches) {
                cursor?.remove();
                return;
            }

            let currentX = -50;
            let currentY = -50;
            let targetX = -50;
            let targetY = -50;
            let animationFrame = null;
            let clickTimer = null;

            const render = () => {
                currentX += (targetX - currentX) * 0.28;
                currentY += (targetY - currentY) * 0.28;
                cursor.style.transform = `translate3d(${currentX}px, ${currentY}px, 0)`;

                if (Math.abs(targetX - currentX) > 0.1 || Math.abs(targetY - currentY) > 0.1) {
                    animationFrame = window.requestAnimationFrame(render);
                } else {
                    animationFrame = null;
                }
            };

            const queueRender = () => {
                if (animationFrame === null) {
                    animationFrame = window.requestAnimationFrame(render);
                }
            };

            document.addEventListener('pointermove', (event) => {
                targetX = event.clientX;
                targetY = event.clientY;
                cursor.classList.add('is-visible');
                queueRender();
            }, { passive: true });

            document.addEventListener('pointerdown', () => {
                cursor.classList.remove('is-clicking');
                void cursor.offsetWidth;
                cursor.classList.add('is-clicking');
                window.clearTimeout(clickTimer);
                clickTimer = window.setTimeout(() => cursor.classList.remove('is-clicking'), 500);
            }, { passive: true });

            window.addEventListener('blur', () => cursor.classList.remove('is-visible'));
            document.documentElement.addEventListener('mouseleave', () => cursor.classList.remove('is-visible'));
        })();

        (() => {
            const lightbox = document.getElementById('globalImageLightbox');
            const image = document.getElementById('globalImageLightboxImage');
            const counter = document.getElementById('globalImageCounter');
            const download = document.getElementById('globalImageDownload');
            let images = [];
            let currentIndex = 0;

            if (!lightbox || !image || !download) return;

            const cleanSrc = (src) => (src || '').split('#')[0];
            const downloadName = (src, alt) => {
                const file = cleanSrc(src).split('?')[0].split('/').pop();
                return file || (alt ? alt.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-') : 'kasbit-image');
            };
            const shouldUseGlobalPreview = (img) => {
                const src = cleanSrc(img.currentSrc || img.src || '');
                if (!src.includes('/uploads/')) return false;
                if (img.closest('.gallery-lightbox, .page-loader, .page-gallery-trigger, [data-document-open]')) return false;

                return true;
            };
            const refreshImages = () => {
                images = Array.from(document.querySelectorAll('img')).filter(shouldUseGlobalPreview);
                images.forEach((img) => {
                    img.classList.add('global-preview-image');
                    img.setAttribute('tabindex', img.getAttribute('tabindex') || '0');
                    img.setAttribute('role', img.getAttribute('role') || 'button');
                });
            };
            const render = (index) => {
                if (!images.length) return;

                currentIndex = (index + images.length) % images.length;
                const item = images[currentIndex];
                const src = item.currentSrc || item.src;
                const alt = item.alt || 'KASBIT image';

                image.src = src;
                image.alt = alt;
                download.href = src;
                download.setAttribute('download', downloadName(src, alt));
                counter.textContent = images.length > 1 ? `${currentIndex + 1} / ${images.length}` : '1 / 1';
                lightbox.querySelectorAll('.gallery-lightbox__nav').forEach((button) => {
                    button.hidden = images.length < 2;
                });
            };
            const open = (img) => {
                refreshImages();
                const index = images.indexOf(img);
                if (index === -1) return;

                render(index);
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.classList.add('gallery-lightbox-open');
            };
            const close = () => {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('gallery-lightbox-open');
                image.src = '';
            };

            document.addEventListener('DOMContentLoaded', refreshImages);
            document.addEventListener('click', (event) => {
                const img = event.target.closest('img.global-preview-image');
                if (!img || !shouldUseGlobalPreview(img)) return;

                const link = img.closest('a[href]');
                if (link && link.hasAttribute('download')) return;

                event.preventDefault();
                open(img);
            });
            document.addEventListener('keydown', (event) => {
                if (!lightbox.classList.contains('is-open')) {
                    const img = event.target.closest?.('img.global-preview-image');
                    if (img && (event.key === 'Enter' || event.key === ' ')) {
                        event.preventDefault();
                        open(img);
                    }
                    return;
                }

                if (event.key === 'Escape') close();
                if (event.key === 'ArrowLeft') render(currentIndex - 1);
                if (event.key === 'ArrowRight') render(currentIndex + 1);
            });

            lightbox.querySelectorAll('[data-global-image-close]').forEach((button) => {
                button.addEventListener('click', close);
            });
            lightbox.querySelector('[data-global-image-prev]')?.addEventListener('click', () => render(currentIndex - 1));
            lightbox.querySelector('[data-global-image-next]')?.addEventListener('click', () => render(currentIndex + 1));
        })();

        (() => {
            const loader = document.getElementById('pageLoader');
            const startedAt = performance.now();
            const minimumDisplay = 250;

            if (!loader) return;

            const showLoader = () => {
                loader.classList.remove('page-loader--hidden');
                document.body.classList.add('page-loading');
            };

            const hideLoader = () => {
                const delay = Math.max(0, minimumDisplay - (performance.now() - startedAt));

                window.setTimeout(() => {
                    loader.classList.add('page-loader--hidden');
                    document.body.classList.remove('page-loading');
                }, delay);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', hideLoader, { once: true });
            } else {
                hideLoader();
            }
            window.addEventListener('pageshow', (event) => {
                if (event.persisted) hideLoader();
            });

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a[href]');

                if (!link
                    || event.defaultPrevented
                    || event.button !== 0
                    || event.ctrlKey
                    || event.metaKey
                    || event.shiftKey
                    || event.altKey
                    || link.target === '_blank'
                    || link.hasAttribute('download')) return;

                const rawHref = (link.getAttribute('href') || '').trim();
                if (rawHref === '' || rawHref === '#') return;

                const destination = new URL(link.href, window.location.href);
                const samePageAnchor = destination.origin === window.location.origin
                    && destination.pathname === window.location.pathname
                    && destination.search === window.location.search
                    && destination.hash;

                if (!samePageAnchor && destination.protocol.startsWith('http')) showLoader();
            });

            document.addEventListener('submit', showLoader);
            window.setTimeout(hideLoader, 1500);
        })();
    </script>
</body>
</html>
