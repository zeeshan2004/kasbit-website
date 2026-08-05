@if(($home->top_header_is_active ?? false))
    @php
        $topLocations = collect([1, 2, 3, 4])->map(fn ($index) => [
            'name' => $home->{"top_location_{$index}_name"} ?? null,
            'url' => $home->{"top_location_{$index}_url"} ?? null,
        ])->filter(fn ($location) => filled($location['name']));
        $loginMenu = $headerMenus->firstWhere('name', 'Login');
        $loginChildren = $loginMenu?->children->where('is_active', true) ?? collect();
        $studentUser = auth('student')->user();
        $loginMenuIcon = match (trim((string) $loginMenu?->icon)) {
            '', 'fa-solid fa-right-to-bracket', 'fa-solid fa-arrow-right-to-bracket' => 'fa-solid fa-user-lock',
            default => $loginMenu->icon,
        };
        $loginToggleLabel = $studentUser
            ? str($studentUser->name)->before(' ')->limit(14)
            : ($loginMenu?->name ?: 'Login');
    @endphp
    <div class="top-header">
        <div class="top-header-inner">
            <div class="top-header-locations">
                @foreach($topLocations as $location)
                    <a href="{{ $location['url'] ?: '#' }}" @if($location['url']) target="_blank" rel="noopener noreferrer" @endif>
                        {{ $location['name'] }}
                    </a>
                @endforeach
            </div>

            <div class="top-header-actions">
                @if($loginMenu)
                    <div class="dropdown top-login-dropdown">
                        <button type="button"
                                class="top-login-toggle"
                                aria-expanded="false"
                                aria-label="Open login links">
                            <i class="{{ $studentUser ? 'fa-solid fa-user-check' : $loginMenuIcon }} top-login-icon" aria-hidden="true"></i>
                            <span>{{ $loginToggleLabel }}</span>
                            <i class="fa-solid fa-chevron-down top-login-chevron" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end top-login-menu">
                            @if($studentUser)
                                <div class="top-login-account">
                                    <strong>{{ $studentUser->name }}</strong>
                                    <span>{{ $studentUser->email }}</span>
                                </div>
                            @else
                                <a class="dropdown-item" href="{{ route('student.login') }}">
                                    <span class="top-login-item-icon" aria-hidden="true">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </span>
                                    <span class="top-login-item-label">Student Sign In</span>
                                    <i class="fa-solid fa-arrow-right top-login-item-arrow" aria-hidden="true"></i>
                                </a>
                                <a class="dropdown-item" href="{{ route('student.register') }}">
                                    <span class="top-login-item-icon" aria-hidden="true">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </span>
                                    <span class="top-login-item-label">Student Registration</span>
                                    <i class="fa-solid fa-arrow-right top-login-item-arrow" aria-hidden="true"></i>
                                </a>
                            @endif

                            <a class="dropdown-item" href="{{ route('feedback.index') }}">
                                <span class="top-login-item-icon" aria-hidden="true">
                                    <i class="fa-solid fa-message"></i>
                                </span>
                                <span class="top-login-item-label">Submit Feedback</span>
                                <i class="fa-solid fa-arrow-right top-login-item-arrow" aria-hidden="true"></i>
                            </a>

                            <div class="top-login-divider" aria-hidden="true"></div>

                            @foreach($loginChildren as $loginChild)
                                @if($loginChild->name === 'Student Login')
                                    @continue
                                @endif
                                @php
                                    $loginChildIcon = match ($loginChild->name) {
                                        'Faculty Login' => 'fa-solid fa-chalkboard-user',
                                        'Results' => 'fa-solid fa-chart-line',
                                        'Convocation Registration' => 'fa-solid fa-file-signature',
                                        default => $loginChild->icon ?: 'fa-solid fa-arrow-right',
                                    };
                                @endphp
                                <a class="dropdown-item" href="{{ $loginChild->link ?: '#' }}">
                                    <span class="top-login-item-icon" aria-hidden="true">
                                        <i class="{{ $loginChildIcon }}"></i>
                                    </span>
                                    <span class="top-login-item-label">{{ $loginChild->name }}</span>
                                    <i class="fa-solid fa-arrow-right top-login-item-arrow" aria-hidden="true"></i>
                                </a>
                            @endforeach

                            @if($studentUser)
                                <div class="top-login-divider" aria-hidden="true"></div>
                                <form method="POST" action="{{ route('student.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item top-login-logout">
                                        <span class="top-login-item-icon" aria-hidden="true">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                        </span>
                                        <span class="top-login-item-label">Sign Out</span>
                                    </button>
                                </form>
                            @elseif($loginChildren->isEmpty())
                                <a class="dropdown-item" href="{{ $loginMenu->link ?: route('student.login') }}">
                                    <span class="top-login-item-icon" aria-hidden="true">
                                        <i class="fa-solid fa-user-lock"></i>
                                    </span>
                                    <span class="top-login-item-label">Login</span>
                                    <i class="fa-solid fa-arrow-right top-login-item-arrow" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
                @if($home->header_phone ?? false)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $home->header_phone) }}" aria-label="Call KASBIT" title="{{ $home->header_phone }}">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                @endif
                @if($home->header_email ?? false)
                    <a href="mailto:{{ $home->header_email }}" aria-label="Email KASBIT" title="{{ $home->header_email }}">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                @endif
                @if($home->header_facebook_url ?? false)
                    <a href="{{ $home->header_facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="KASBIT on Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                @endif
                @if($home->header_twitter_url ?? false)
                    <a href="{{ $home->header_twitter_url }}" target="_blank" rel="noopener noreferrer" aria-label="KASBIT on X">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                @endif
                @if($home->header_instagram_url ?? false)
                    <a href="{{ $home->header_instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="KASBIT on Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
    <script>
    (function(){
        var d = document.querySelector('.top-login-dropdown');
        if (!d) return;
        var btn = d.querySelector('.top-login-toggle');
        var menu = d.querySelector('.dropdown-menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function(e){
            e.stopPropagation();
            var open = menu.classList.contains('show');
            menu.classList.toggle('show', !open);
            btn.classList.toggle('show', !open);
        });
        document.addEventListener('click', function(e){
            if (!d.contains(e.target)){
                menu.classList.remove('show');
                btn.classList.remove('show');
            }
        });
    })();
    </script>
@endif

<header class="site-header sticky-top">
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-xxl header-navbar">
            <a class="navbar-brand header-brand m-0" href="{{ url('/') }}">
                @if($home->header_logo_url ?? false)
                    <span class="start-menu-button">
                        <span class="start-menu-inner">
                            <img src="{{ asset($home->header_logo_url) }}" alt="KASBIT logo" class="kasbit-logo">
                            <span class="logo-colors" aria-hidden="true">
                                <span class="logo-glow logo-glow-1"></span>
                                <span class="logo-glow logo-glow-2"></span>
                            </span>
                        </span>
                    </span>
                @endif
                <span class="header-wordmark">KASBIT</span>
            </a>

            <button class="navbar-toggler header-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto header-nav">
                    @forelse($headerMenus as $menu)
                        @if(in_array($menu->name, ['ORIC', 'Login'], true))
                            @continue
                        @endif

                        @if($menu->name === 'QEC')
                            @php
                                $oricMenu = $headerMenus->firstWhere('name', 'ORIC');
                                $qecChildren = $menu->children->where('is_active', true);
                                $oricChildren = $oricMenu?->children->where('is_active', true) ?? collect();
                            @endphp
                            <li class="nav-item dropdown mega-menu-item">
                                <a class="nav-link dropdown-toggle"
                                   href="#"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   data-bs-auto-close="outside"
                                   aria-expanded="false">
                                    QEC &amp; ORIC
                                </a>
                                <div class="dropdown-menu qec-oric-menu">
                                    <div class="qec-oric-grid">
                                        <section class="qec-oric-column">
                                            <a href="{{ $menu->link ?: '#' }}" class="qec-oric-heading">
                                                <i class="{{ $menu->icon ?: 'fa-solid fa-shield-halved' }}"></i>
                                                QEC
                                            </a>
                                            <div class="qec-oric-links">
                                                @foreach($qecChildren as $child)
                                                    @php
                                                        $activeGrandchildren = $child->children->where('is_active', true);
                                                    @endphp
                                                    <div class="qec-oric-link-group {{ $activeGrandchildren->count() ? 'has-subitems' : '' }}">
                                                        @if($activeGrandchildren->count())
                                                            <div class="qec-oric-parent-row">
                                                                <a class="dropdown-item qec-oric-parent-link" href="{{ $child->link ?: '#' }}">{{ $child->name }}</a>
                                                                <button type="button"
                                                                        class="qec-oric-submenu-toggle"
                                                                        aria-label="Open {{ $child->name }} menu"
                                                                        aria-expanded="false">
                                                                    <i class="fa-solid fa-chevron-right"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <a class="dropdown-item" href="{{ $child->link ?: '#' }}">{{ $child->name }}</a>
                                                        @endif
                                                        @if($activeGrandchildren->count())
                                                            <div class="qec-oric-subitems">
                                                                @foreach($activeGrandchildren as $grandchild)
                                                                    <a class="dropdown-item qec-oric-subitem" href="{{ $grandchild->link ?: '#' }}">{{ $grandchild->name }}</a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </section>

                                        <section class="qec-oric-column">
                                            <a href="{{ $oricMenu?->link ?: '#' }}" class="qec-oric-heading">
                                                <i class="{{ $oricMenu?->icon ?: 'fa-solid fa-flask' }}"></i>
                                                ORIC
                                            </a>
                                            <div class="qec-oric-links">
                                                @foreach($oricChildren as $child)
                                                    @php
                                                        $activeGrandchildren = $child->children->where('is_active', true);
                                                    @endphp
                                                    <div class="qec-oric-link-group {{ $activeGrandchildren->count() ? 'has-subitems' : '' }}">
                                                        @if($activeGrandchildren->count())
                                                            <div class="qec-oric-parent-row">
                                                                <a class="dropdown-item qec-oric-parent-link" href="{{ $child->link ?: '#' }}">{{ $child->name }}</a>
                                                                <button type="button"
                                                                        class="qec-oric-submenu-toggle"
                                                                        aria-label="Open {{ $child->name }} menu"
                                                                        aria-expanded="false">
                                                                    <i class="fa-solid fa-chevron-right"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <a class="dropdown-item" href="{{ $child->link ?: '#' }}">{{ $child->name }}</a>
                                                        @endif
                                                        @if($activeGrandchildren->count())
                                                            <div class="qec-oric-subitems">
                                                                @foreach($activeGrandchildren as $grandchild)
                                                                    <a class="dropdown-item qec-oric-subitem" href="{{ $grandchild->link ?: '#' }}">{{ $grandchild->name }}</a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </li>
                            @continue
                        @endif

                        @if($menu->children->where('is_active', true)->count())
                            <li class="nav-item dropdown">
                                @php
                                    $menuLink = trim((string) $menu->link);
                                    $hasPageLink = $menuLink !== '' && $menuLink !== '#';
                                    $hasFlyoutSubmenu = $menu->children->where('is_active', true)
                                        ->filter(fn ($child) => $child->children->where('is_active', true)->count())
                                        ->isNotEmpty();
                                @endphp
                                @if($hasPageLink)
                                    <div class="nav-split-link">
                                        <a class="nav-link nav-primary-page-link" href="{{ $menuLink }}">
                                            {{ $menu->name }}
                                        </a>
                                        <button type="button"
                                                class="nav-dropdown-trigger dropdown-toggle responsive-dropdown-trigger"
                                                data-split-dropdown-toggle
                                                aria-expanded="false"
                                                aria-label="Open {{ $menu->name }} menu">
                                            <span class="responsive-dropdown-label">{{ $menu->name }}</span>
                                        </button>
                                    </div>
                                @else
                                    <button type="button" class="nav-link dropdown-toggle nav-link-button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $menu->name }}
                                    </button>
                                @endif
                                <ul class="dropdown-menu {{ $menu->name === 'Programs' ? 'programs-dropdown-menu' : '' }} {{ $hasFlyoutSubmenu ? 'has-flyout-submenu' : '' }}">
                                    @foreach($menu->children->where('is_active', true) as $child)
                                        @php
                                            $activeGrandchildren = $child->children->where('is_active', true);
                                        @endphp
                                        <li class="{{ $activeGrandchildren->count() ? 'program-menu-group' : '' }}">
                                            <?php
                                                $childLink = $child->name === 'About Us'
                                                    ? route('about', [], false)
                                                    : trim((string) $child->link);
                                                $childHasLink = $childLink !== '' && $childLink !== '#';
                                            ?>
                                            @if($activeGrandchildren->count())
                                                <div class="program-menu-row">
                                                    @if($childHasLink)
                                                        <a class="dropdown-item program-menu-link" href="{{ $childLink }}">{{ $child->name }}</a>
                                                    @else
                                                        <button type="button" class="dropdown-item program-menu-link program-group-label">{{ $child->name }}</button>
                                                    @endif
                                                    <button type="button" class="program-submenu-toggle" aria-label="Open {{ $child->name }} menu" aria-expanded="false">
                                                        <i class="fa-solid fa-chevron-right"></i>
                                                    </button>
                                                </div>
                                                <ul class="program-submenu">
                                                    @foreach($activeGrandchildren as $grandchild)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ $grandchild->link ?: '#' }}">
                                                                {{ $grandchild->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                @if($childHasLink)
                                                    <a class="dropdown-item" href="{{ $childLink }}">{{ $child->name }}</a>
                                                @else
                                                    <button type="button" class="dropdown-item program-group-label">{{ $child->name }}</button>
                                                @endif
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $menu->link ?: '#' }}">{{ $menu->name }}</a>
                            </li>
                        @endif
                    @empty
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    @endforelse
                </ul>

                <div class="header-actions d-flex align-items-center gap-2">
                    <a href="#" class="header-cta">
                        <span>Apply Now</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>

@once
    @push('scripts')
        <script>
            (() => {
                const navbar = document.querySelector('.header-navbar');
                const collapse = navbar?.querySelector('.navbar-collapse');

                document.querySelectorAll('.program-submenu-toggle').forEach((button) => {
                    button.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        const group = button.closest('.program-menu-group');
                        if (!group) return;

                        const willOpen = !group.classList.contains('is-open');

                        // Close other open groups within the same dropdown.
                        group.closest('.dropdown-menu')
                            ?.querySelectorAll('.program-menu-group.is-open')
                            .forEach((other) => {
                                if (other !== group) {
                                    other.classList.remove('is-open');
                                    other.querySelector('.program-submenu-toggle')
                                        ?.setAttribute('aria-expanded', 'false');
                                }
                            });

                        group.classList.toggle('is-open', willOpen);
                        button.setAttribute('aria-expanded', String(willOpen));
                    });
                });

                document.querySelectorAll('.qec-oric-submenu-toggle').forEach((button) => {
                    button.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        const group = button.closest('.qec-oric-link-group');
                        if (!group) return;

                        const willOpen = !group.classList.contains('is-open');

                        group.closest('.qec-oric-menu')
                            ?.querySelectorAll('.qec-oric-link-group.is-open')
                            .forEach((other) => {
                                if (other !== group) {
                                    other.classList.remove('is-open');
                                    other.querySelector('.qec-oric-submenu-toggle')
                                        ?.setAttribute('aria-expanded', 'false');
                                }
                            });

                        group.classList.toggle('is-open', willOpen);
                        button.setAttribute('aria-expanded', String(willOpen));
                    });
                });

                document.addEventListener('click', (event) => {
                    if (event.target.closest('.program-menu-group, .qec-oric-link-group')) return;

                    document.querySelectorAll('.program-menu-group.is-open').forEach((group) => {
                        group.classList.remove('is-open');
                        group.querySelector('.program-submenu-toggle')
                            ?.setAttribute('aria-expanded', 'false');
                    });

                    document.querySelectorAll('.qec-oric-link-group.is-open').forEach((group) => {
                        group.classList.remove('is-open');
                        group.querySelector('.qec-oric-submenu-toggle')
                            ?.setAttribute('aria-expanded', 'false');
                    });
                });

                document.querySelectorAll('[data-split-dropdown-toggle]').forEach((button) => {
                    button.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        const dropdown = button.closest('.dropdown');
                        const menu = dropdown?.querySelector(':scope > .dropdown-menu');
                        if (!menu) return;

                        const willOpen = !menu.classList.contains('show');

                        document.querySelectorAll('[data-split-dropdown-toggle]').forEach((otherButton) => {
                            const otherMenu = otherButton.closest('.dropdown')?.querySelector(':scope > .dropdown-menu');
                            otherMenu?.classList.remove('show');
                            otherButton.setAttribute('aria-expanded', 'false');
                        });

                        menu.classList.toggle('show', willOpen);
                        button.setAttribute('aria-expanded', String(willOpen));
                    });
                });

                document.addEventListener('click', (event) => {
                    if (event.target.closest('.nav-split-link, .nav-split-link + .dropdown-menu')) return;

                    document.querySelectorAll('[data-split-dropdown-toggle]').forEach((button) => {
                        button.closest('.dropdown')?.querySelector(':scope > .dropdown-menu')?.classList.remove('show');
                        button.setAttribute('aria-expanded', 'false');
                    });
                });

                if (navbar) {
                    const navDropdowns = navbar.querySelectorAll('.header-nav .nav-item.dropdown');

                    navDropdowns.forEach((item) => {
                        item.addEventListener('mouseenter', () => {
                            if (window.innerWidth < 1400 || navbar.classList.contains('header-force-collapse')) return;

                            navDropdowns.forEach((other) => {
                                if (other === item) return;

                                const openMenu = other.querySelector(':scope > .dropdown-menu.show');
                                if (!openMenu) return;

                                const bsToggle = other.querySelector('[data-bs-toggle="dropdown"]');
                                if (bsToggle && window.bootstrap?.Dropdown) {
                                    window.bootstrap.Dropdown.getOrCreateInstance(bsToggle).hide();
                                } else {
                                    openMenu.classList.remove('show');
                                }

                                other.querySelectorAll('[aria-expanded="true"]')
                                    .forEach((toggle) => toggle.setAttribute('aria-expanded', 'false'));
                            });
                        });
                    });
                }

                if (!navbar || !collapse || typeof ResizeObserver === 'undefined') return;

                let frame;

                const fitHeaderMenu = () => {
                    window.cancelAnimationFrame(frame);
                    frame = window.requestAnimationFrame(() => {
                        navbar.classList.remove('header-force-collapse');

                        if (window.innerWidth < 1400) return;

                        const overflowed = navbar.scrollWidth > navbar.clientWidth + 2
                            || collapse.scrollWidth > collapse.clientWidth + 2;

                        navbar.classList.toggle('header-force-collapse', overflowed);
                    });
                };

                new ResizeObserver(fitHeaderMenu).observe(navbar);
                window.addEventListener('resize', fitHeaderMenu, { passive: true });
                document.fonts?.ready.then(fitHeaderMenu);
                fitHeaderMenu();

                // Close mobile menu when a link is clicked
                if (collapse) {
                    collapse.addEventListener('click', (event) => {
                        const link = event.target.closest('a[href]');
                        if (!link) return;

                        const href = (link.getAttribute('href') || '').trim();
                        if (href === '' || href === '#') return;

                        // Only close on mobile (when collapse is visible)
                        if (window.innerWidth < 1400 || navbar.classList.contains('header-force-collapse')) {
                            const bsCollapse = window.bootstrap?.Collapse?.getInstance(collapse);
                            if (bsCollapse) {
                                bsCollapse.hide();
                            } else {
                                collapse.classList.remove('show');
                            }
                        }
                    });
                }

                // Close any open dropdown when a dropdown-item link is clicked
                document.querySelectorAll('.dropdown-menu').forEach((menu) => {
                    menu.addEventListener('click', (event) => {
                        const link = event.target.closest('a[href], button[type="submit"]');
                        if (!link) return;

                        const href = link.getAttribute('href') || '';
                        const isSubmit = link.type === 'submit';
                        if (!isSubmit && (href.trim() === '' || href.trim() === '#')) return;

                        // Close the dropdown
                        const dropdown = menu.closest('.dropdown');
                        const toggle = dropdown?.querySelector('[data-bs-toggle="dropdown"], .top-login-toggle');
                        menu.classList.remove('show');
                        if (toggle) {
                            toggle.classList.remove('show');
                            toggle.setAttribute('aria-expanded', 'false');
                        }
                    });
                });
            })();
        </script>
    @endpush
@endonce
