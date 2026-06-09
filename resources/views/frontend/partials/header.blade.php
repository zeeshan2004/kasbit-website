@if(($home->top_header_is_active ?? false))
    @php
        $topLocations = collect([1, 2, 3])->map(fn ($index) => [
            'name' => $home->{"top_location_{$index}_name"} ?? null,
            'url' => $home->{"top_location_{$index}_url"} ?? null,
        ])->filter(fn ($location) => filled($location['name']));
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
@endif

<header class="site-header sticky-top">
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg header-navbar">
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
                        @if($menu->children->where('is_active', true)->count())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="{{ $menu->link ?: '#' }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $menu->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($menu->children->where('is_active', true) as $child)
                                        <li>
                                            <a class="dropdown-item" href="{{ $child->link ?: '#' }}">{{ $child->name }}</a>
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
                    @if($home->header_phone ?? false)
                        <div class="dropdown">
                            <button type="button"
                                    class="header-contact-action"
                                    data-bs-toggle="dropdown"
                                    data-bs-display="static"
                                    aria-expanded="false"
                                    aria-label="Show contact details">
                                <i class="fa-solid fa-phone"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end header-contact-menu">
                                <span class="header-contact-label">Contact Number</span>
                                <strong>{{ $home->header_phone }}</strong>
                                @if($home->header_email ?? false)
                                    <span class="header-contact-label mt-3">Email Address</span>
                                    <span>{{ $home->header_email }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    <a href="#" class="header-cta">
                        <span>Apply Now</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>
