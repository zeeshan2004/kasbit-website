<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preload"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet"></noscript>

    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
</head>
<body>
    <div id="pageLoader" class="page-loader page-loader--hidden" role="status" aria-live="polite" aria-label="Loading page">
        <div class="page-loader__content">
            <div class="page-loader__spinner">
                <span class="page-loader__ring" aria-hidden="true"></span>
                @if(($home ?? null)?->header_logo_url)
                    <img src="{{ asset($home->header_logo_url) }}" alt="" class="page-loader__logo">
                @else
                    <i class="fa-solid fa-graduation-cap page-loader__fallback-icon" aria-hidden="true"></i>
                @endif
            </div>
            <div class="page-loader__text">Loading...</div>
        </div>
    </div>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
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
