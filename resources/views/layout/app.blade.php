<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
</head>
<body class="page-loading">
    <div id="pageLoader" class="page-loader" role="status" aria-live="polite" aria-label="Loading page">
        <div class="page-loader__content">
            <div class="page-loader__spinner">
                <span class="page-loader__ring" aria-hidden="true"></span>
                <img src="{{ asset('uploads/home/1780512372_logo.png') }}" alt="" class="page-loader__logo">
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
            const minimumDisplay = 650;

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

            window.addEventListener('load', hideLoader, { once: true });
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
            window.setTimeout(hideLoader, 8000);
        })();
    </script>
</body>
</html>
