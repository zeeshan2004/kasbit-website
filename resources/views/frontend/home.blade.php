@extends('layout.app')


@section('content')

@include('frontend.partials.header')


<!-- HERO CAROUSEL -->
@if($heroSlides->count())
    <section id="heroCarousel"
             class="hero-section carousel slide"
             data-bs-ride="carousel"
             data-bs-interval="5000"
             data-bs-pause="false"
             data-bs-wrap="true">
        <div class="carousel-indicators">
            @foreach($heroSlides as $slide)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Carousel Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach($heroSlides as $slide)
                @php
                    $slideDimensions = $slide->image_dimensions;
                @endphp
                <div class="carousel-item hero-slide {{ $loop->first ? 'active' : '' }}">
                    <picture>
                        @if($slide->image_avif_url)
                            <source srcset="{{ asset($slide->image_avif_url) }}" type="image/avif">
                        @endif
                        <source srcset="{{ asset($slide->image_url) }}" type="image/webp">
                        <img src="{{ asset($slide->image_url) }}"
                             class="hero-image"
                             alt="{{ $slide->title ?: 'KASBIT carousel slide' }}"
                             width="{{ $slideDimensions['width'] }}"
                             height="{{ $slideDimensions['height'] }}"
                             @if($loop->first)
                                 fetchpriority="high"
                                 loading="eager"
                                 decoding="async"
                             @else
                                 fetchpriority="low"
                                 loading="lazy"
                                 decoding="async"
                             @endif>
                    </picture>
                    @if($slide->title || $slide->subtitle || $slide->button_text)
                        <div class="hero-overlay">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-7">
                                    @if($slide->title)
                                        <h1 class="display-2 fw-bold text-white">
                                            {{ $slide->title }}
                                        </h1>
                                    @endif
                                    @if($slide->subtitle)
                                        <p class="lead text-white mt-4">
                                            {{ $slide->subtitle }}
                                        </p>
                                    @endif
                                    @if($slide->button_text)
                                        <div class="mt-4">
                                            <a href="{{ $slide->button_link ?: '#' }}" class="btn btn-warning btn-lg">
                                                {{ $slide->button_text }}
                                            </a>
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </section>
@else
    @php
        $fallbackHeroImage = $home->hero_image ?? 'images/hero.webp';
        $fallbackHeroAvif = preg_replace('/\.[^.]+$/', '.avif', $fallbackHeroImage);
        $fallbackHeroDimensions = @getimagesize(public_path($fallbackHeroImage));
    @endphp
    <section class="hero-section position-relative">
        <picture>
            @if($fallbackHeroAvif && is_file(public_path($fallbackHeroAvif)))
                <source srcset="{{ asset($fallbackHeroAvif) }}" type="image/avif">
            @endif
            <source srcset="{{ asset($fallbackHeroImage) }}" type="image/webp">
            <img src="{{ asset($fallbackHeroImage) }}"
                 class="hero-image"
                 alt="KASBIT"
                 width="{{ $fallbackHeroDimensions[0] ?? 1600 }}"
                 height="{{ $fallbackHeroDimensions[1] ?? 563 }}"
                 fetchpriority="high"
                 loading="eager"
                 decoding="async">
        </picture>
        <div class="hero-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <span class="badge bg-warning text-dark mb-3">
                            Admissions Open 2026
                        </span>
                        <h1 class="display-2 fw-bold text-white">
                            {{ $home->hero_title ?? 'Shape Your Future With Excellence' }}
                        </h1>
                        <p class="lead text-white mt-4">
                            {{ $home->hero_subtitle ?? 'Modern education for future leaders and innovators.' }}
                        </p>
                        <div class="mt-4">
                            <a href="#" class="btn btn-warning btn-lg me-3">
                                Explore Programs
                            </a>
                            <a href="#" class="btn btn-outline-light btn-lg">
                                Virtual Tour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@if($heroSlides->count() > 1)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const carouselElement = document.getElementById('heroCarousel');

                if (carouselElement) {
                    const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement, {
                        interval: 5000,
                        pause: false,
                        ride: 'carousel',
                        wrap: true
                    });
                    let dragStartX = 0;
                    let activePointerId = null;
                    let dragged = false;

                    carousel.cycle();

                    carouselElement.addEventListener('pointerdown', function (event) {
                        if (event.pointerType === 'mouse' && event.button !== 0) return;
                        if (event.target.closest('a, button')) return;

                        activePointerId = event.pointerId;
                        dragStartX = event.clientX;
                        dragged = false;
                        carouselElement.classList.add('is-dragging');
                        carouselElement.setPointerCapture(event.pointerId);
                        carousel.pause();
                    });

                    carouselElement.addEventListener('pointermove', function (event) {
                        if (event.pointerId !== activePointerId) return;
                        dragged = Math.abs(event.clientX - dragStartX) > 8;
                    });

                    const finishDrag = function (event) {
                        if (event.pointerId !== activePointerId) return;

                        const distance = event.clientX - dragStartX;
                        carouselElement.classList.remove('is-dragging');

                        if (carouselElement.hasPointerCapture(event.pointerId)) {
                            carouselElement.releasePointerCapture(event.pointerId);
                        }

                        activePointerId = null;

                        if (dragged && Math.abs(distance) >= 45) {
                            distance < 0 ? carousel.next() : carousel.prev();
                        }

                        carousel.cycle();
                    };

                    carouselElement.addEventListener('pointerup', finishDrag);
                    carouselElement.addEventListener('pointercancel', finishDrag);
                    carouselElement.addEventListener('lostpointercapture', function () {
                        activePointerId = null;
                        carouselElement.classList.remove('is-dragging');
                        carousel.cycle();
                    });
                }
            });
        </script>
    @endpush
@endif


<!-- ABOUT -->
<section class="about-section">
    <div class="container">
        <div class="row g-5 align-items-stretch">
            <div class="col-lg-6">
                <div class="about-intro">
                    <span class="about-label">
                        {{ $home->about_label ?? 'WELCOME TO' }}
                    </span>
                    @php
                        $aboutTitle = $home->about_title ?? 'KASB Institute of Technology';
                        $aboutTitleParts = preg_split('/\s+/', trim($aboutTitle), 2);
                    @endphp
                    <h2 class="about-title">
                        <span class="title-accent">{{ $aboutTitleParts[0] ?? '' }}</span>
                        @if(isset($aboutTitleParts[1]))
                            <span class="title-primary">{{ $aboutTitleParts[1] }}</span>
                        @endif
                    </h2>
                    <div class="about-description">
                        {!! nl2br(e($home->about_description ?? 'KASB Institute of Technology is committed to academic excellence, innovation and responsible leadership.')) !!}
                    </div>

                    @if($home->about_image ?? false)
                        <img src="{{ asset($home->about_image) }}"
                             loading="lazy"
                             decoding="async"
                             fetchpriority="low"
                             alt="{{ $home->about_title ?? 'About KASBIT' }}"
                             class="about-image">
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="vision-mission-panel">
                    <div class="vision-mission-item">
                        <div class="vision-mission-heading">
                            <span class="vision-mission-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </span>
                            @php
                                $visionTitle = $home->vision_title ?? 'The Vision of KASBIT';
                                $visionTitleParts = preg_split('/\s+(?=of\b)/i', trim($visionTitle), 2);
                            @endphp
                            <h3>
                                <span class="title-accent">{{ $visionTitleParts[0] ?? '' }}</span>
                                @if(isset($visionTitleParts[1]))
                                    <span class="title-primary">{{ $visionTitleParts[1] }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="vision-mission-text">
                            {!! nl2br(e($home->vision ?? 'Promoting excellence in education through holistic, transformative and innovative learning.')) !!}
                        </div>
                    </div>

                    <div class="vision-mission-item">
                        <div class="vision-mission-heading">
                            <span class="vision-mission-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            @php
                                $missionTitle = $home->mission_title ?? 'The Mission of KASBIT';
                                $missionTitleParts = preg_split('/\s+(?=of\b)/i', trim($missionTitle), 2);
                            @endphp
                            <h3>
                                <span class="title-accent">{{ $missionTitleParts[0] ?? '' }}</span>
                                @if(isset($missionTitleParts[1]))
                                    <span class="title-primary">{{ $missionTitleParts[1] }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="vision-mission-text">
                            {!! nl2br(e($home->mission ?? 'Cultivating value-based growth through research, creativity and entrepreneurship.')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- NEWS -->
<section class="news-section" @if($home->news_bg ?? false) style="background-image:url('{{ asset($home->news_bg) }}')" @endif>
    <div class="news-section-overlay"></div>
    <div class="container">
        <div class="position-relative">
            @if($news->count())
                <div id="newsEventsCarousel" class="carousel slide news-carousel" data-bs-ride="carousel" data-bs-interval="5000">
                    @if($news->count() > 1)
                        <div class="carousel-indicators">
                            @foreach($news as $item)
                                <button type="button"
                                        data-bs-target="#newsEventsCarousel"
                                        data-bs-slide-to="{{ $loop->index }}"
                                        class="{{ $loop->first ? 'active' : '' }}"
                                        aria-current="{{ $loop->first ? 'true' : 'false' }}"
                                        aria-label="News slide {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>
                    @endif

                    <div class="carousel-inner">
                        @foreach($news as $item)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <article class="news-carousel-card">
                                    <div class="news-carousel-image">
                                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->title }}" loading="lazy" decoding="async" fetchpriority="low">
                                    </div>
                                    <div class="news-carousel-content">
                                        <h3>{{ $item->title }}</h3>
                                        @if($item->description)
                                            <div class="news-carousel-copy">{!! nl2br(e($item->description)) !!}</div>
                                        @endif
                                        @if($item->link && $item->link !== '#')
                                            <a href="{{ $item->link }}" class="btn news-read-more">
                                                Read More
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    @if($news->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#newsEventsCarousel" data-bs-slide="prev" aria-label="Previous news">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#newsEventsCarousel" data-bs-slide="next" aria-label="Next news">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    @endif
                </div>
            @else
                <div class="news-empty-state">
                    News and updates will be available soon.
                </div>
            @endif
        </div>
    </div>
</section>


<!-- LOCATIONS -->
<section class="locations-section">

    <div class="container">

        <div class="text-center mb-5">

            <span class="section-tag">
                LOCATIONS
            </span>

            <h2 class="display-5 fw-bold">
                {{ $home->location_title ?? 'Our Locations' }}
            </h2>

            <p class="text-muted mt-3">
                {{ $home->location_description ?? 'KASB Institute of Technology has three locations for a unique study experience.' }}
            </p>

        </div>

        <div class="row g-4">

            @if($home->location1_name)
            @php
                $location1MapUrl = trim($home->location1_map_url ?? '');
                if ($location1MapUrl && !preg_match('/^https?:\/\//i', $location1MapUrl)) {
                    $location1MapUrl = 'https://' . $location1MapUrl;
                }
            @endphp
            <div class="col-lg-4 col-md-6">
                <a href="{{ $location1MapUrl ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($home->location1_name . ' KASBIT Karachi') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="location-link"
                   aria-label="Open {{ $home->location1_name }} in Google Maps">
                    <div class="location-card">
                        @if($home->location1_image)
                            <img src="{{ asset($home->location1_image) }}" class="location-card-image" alt="{{ $home->location1_name }}" loading="lazy" decoding="async" fetchpriority="low">
                        @endif
                        <div class="location-card-body">
                            <h3>{{ $home->location1_name }}</h3>
                            <span class="location-map-label"><i class="fa-solid fa-location-dot"></i> View on Google Maps</span>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($home->location2_name)
            @php
                $location2MapUrl = trim($home->location2_map_url ?? '');
                if ($location2MapUrl && !preg_match('/^https?:\/\//i', $location2MapUrl)) {
                    $location2MapUrl = 'https://' . $location2MapUrl;
                }
            @endphp
            <div class="col-lg-4 col-md-6">
                <a href="{{ $location2MapUrl ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($home->location2_name . ' KASBIT Karachi') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="location-link"
                   aria-label="Open {{ $home->location2_name }} in Google Maps">
                    <div class="location-card">
                        @if($home->location2_image)
                            <img src="{{ asset($home->location2_image) }}" class="location-card-image" alt="{{ $home->location2_name }}" loading="lazy" decoding="async" fetchpriority="low">
                        @endif
                        <div class="location-card-body">
                            <h3>{{ $home->location2_name }}</h3>
                            <span class="location-map-label"><i class="fa-solid fa-location-dot"></i> View on Google Maps</span>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($home->location3_name)
            @php
                $location3MapUrl = trim($home->location3_map_url ?? '');
                if ($location3MapUrl && !preg_match('/^https?:\/\//i', $location3MapUrl)) {
                    $location3MapUrl = 'https://' . $location3MapUrl;
                }
            @endphp
            <div class="col-lg-4 col-md-6">
                <a href="{{ $location3MapUrl ?: 'https://www.google.com/maps/search/?api=1&query=' . urlencode($home->location3_name . ' KASBIT Karachi') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="location-link"
                   aria-label="Open {{ $home->location3_name }} in Google Maps">
                    <div class="location-card">
                        @if($home->location3_image)
                            <img src="{{ asset($home->location3_image) }}" class="location-card-image" alt="{{ $home->location3_name }}" loading="lazy" decoding="async" fetchpriority="low">
                        @endif
                        <div class="location-card-body">
                            <h3>{{ $home->location3_name }}</h3>
                            <span class="location-map-label"><i class="fa-solid fa-location-dot"></i> View on Google Maps</span>
                        </div>
                    </div>
                </a>
            </div>
            @endif

        </div>

    </div>

</section>


@php
    $videoTourEmbedUrl = null;
    $videoTourUrl = trim($home->video_tour_url ?? '');

    if ($videoTourUrl && preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([A-Za-z0-9_-]{11})~', $videoTourUrl, $videoMatch)) {
        $videoTourEmbedUrl = 'https://www.youtube.com/embed/' . $videoMatch[1] . '?rel=0&enablejsapi=1&playsinline=1&mute=1&controls=0&disablekb=1&fs=0&iv_load_policy=3';
    }
@endphp

@if(($home->video_tour_is_active ?? false) && (($home->video_tour_file ?? false) || $videoTourEmbedUrl))
    <section class="video-tour-section">
        <div class="container">
            <div class="video-tour-heading">
                <h2>{{ $home->video_tour_title ?: 'VIDEO TOUR OF KASBIT' }}</h2>
                <span></span>
            </div>

            <div class="video-tour-frame">
                @if($home->video_tour_file ?? false)
                    <video id="kasbitVideoTour" muted playsinline preload="none" @if($home->video_tour_poster ?? false) poster="{{ asset($home->video_tour_poster) }}" @endif>
                        <source src="{{ asset($home->video_tour_file) }}">
                        Your browser does not support the video player.
                    </video>
                @else
                    <iframe
                        id="kasbitVideoTourYoutube"
                        src="{{ $videoTourEmbedUrl }}"
                        title="{{ $home->video_tour_title ?: 'VIDEO TOUR OF KASBIT' }}"
                        loading="lazy"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                @endif
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const section = document.querySelector('.video-tour-section');
                const video = document.getElementById('kasbitVideoTour');
                const youtube = document.getElementById('kasbitVideoTourYoutube');

                if (!section || (!video && !youtube)) return;

                const observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        const isVisible = entry.isIntersecting && entry.intersectionRatio >= 0.55;

                        if (video) {
                            if (isVisible) {
                                video.play().catch(function () {});
                            } else {
                                video.pause();
                            }
                        }

                        if (youtube?.contentWindow) {
                            youtube.contentWindow.postMessage(JSON.stringify({
                                event: 'command',
                                func: isVisible ? 'playVideo' : 'pauseVideo',
                                args: []
                            }), '*');
                        }
                    });
                }, {
                    threshold: [0, 0.55]
                });

                observer.observe(section);
            });
        </script>
    @endpush
@endif


<section class="affiliations-section" aria-labelledby="affiliations-title">
    <div class="container affiliations-inner">
        <div class="affiliations-heading">
            <span class="affiliations-kicker">Recognized Standards &amp; Partnerships</span>
            <h2 id="affiliations-title">NATIONAL &amp; INTERNATIONAL AFFILIATIONS/ ACCREDITATIONS</h2>
            <span class="affiliations-heading-line"></span>
        </div>

        <div class="affiliations-grid">
            <div class="affiliation-item">
                <div class="affiliation-mark affiliation-mark-urs">
                    <i class="fa-solid fa-certificate"></i>
                    <strong>URS</strong>
                </div>
                <div class="affiliation-copy">
                    <strong>URS / UKAS</strong>
                    <span>Quality Accreditation</span>
                </div>
            </div>

            <div class="affiliation-item">
                <div class="affiliation-mark affiliation-mark-sap">
                    <strong>SAP</strong>
                </div>
                <div class="affiliation-copy">
                    <strong>SAP University Alliances</strong>
                    <span>Academic Partnership</span>
                </div>
            </div>

            <div class="affiliation-item">
                <div class="affiliation-mark affiliation-mark-icc">
                    <i class="fa-solid fa-globe"></i>
                    <strong>ICC</strong>
                </div>
                <div class="affiliation-copy">
                    <strong>International Collaboration</strong>
                    <span>Global Network</span>
                </div>
            </div>

            <div class="affiliation-item">
                <div class="affiliation-mark affiliation-mark-gov">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="affiliation-copy">
                    <strong>Government Accreditation</strong>
                    <span>Official Recognition</span>
                </div>
            </div>

            <div class="affiliation-item">
                <div class="affiliation-mark affiliation-mark-apup">
                    <strong>APUP</strong>
                </div>
                <div class="affiliation-copy">
                    <strong>APUP</strong>
                    <span>Institutional Association</span>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="founder-vision-section" aria-labelledby="founder-vision-title">
    <div class="container">
        <div class="founder-vision-layout">
            <div class="founder-portrait-wrap" aria-hidden="true">
                <div class="founder-hexagon-border"></div>
                <div class="founder-hexagon-inner">
                    <img src="{{ asset('images/khadim-ali-shah-bukhari.webp') }}"
                         loading="lazy"
                         decoding="async"
                         fetchpriority="low"
                         alt="Khadim Ali Shah Bukhari"
                         class="founder-portrait-image">
                </div>
            </div>

            <div class="founder-vision-copy">
                <span class="founder-vision-kicker">A Vision That Built an Institution</span>
                <h2 id="founder-vision-title">Khadim Ali Shah Bukhari's Vision</h2>
                <blockquote>
                    "Build an institution whose name would become a synonym for international quality education."
                </blockquote>
                <p>
                    This enduring vision continues to guide KASB Institute of Technology in developing capable,
                    responsible and future-ready graduates.
                </p>
                <strong class="founder-name">Khadim Ali Shah Bukhari (Late)</strong>
            </div>
        </div>
    </div>
</section>


@include('frontend.partials.footer')

@endsection
