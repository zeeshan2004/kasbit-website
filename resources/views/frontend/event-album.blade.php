@extends('layout.app')

@section('content')
    @include('frontend.partials.header')

    <main class="cms-content-page" style="--page-accent:{{ $page->accent_color ?: '#07559d' }}">
        <section class="cms-content-hero">
            <div class="container">
                <span class="cms-content-eyebrow">
                    <a href="{{ url('/pages/' . $page->slug) }}" style="color:inherit;text-decoration:none;">{{ $page->title }}</a>
                </span>
                <h1>{{ $album->title }}</h1>
            </div>
        </section>

        <section class="page-gallery-section">
            <div class="container">
                <div class="mb-6">
                    <a href="{{ url('/pages/' . $page->slug) }}" class="event-album-back">
                        <i class="fa-solid fa-arrow-left"></i> Back to {{ $page->title }}
                    </a>
                </div>

                @if($album->images->count())
                    <div class="page-gallery-grid">
                        @foreach($album->images as $image)
                            @php
                                $imageUrl = asset($image->image) . '?v=' . $image->updated_at?->timestamp;
                                $downloadName = \Illuminate\Support\Str::slug($album->title . '-' . $loop->iteration) . '.' . pathinfo($image->image, PATHINFO_EXTENSION);
                            @endphp
                            <figure class="page-gallery-item">
                                <button type="button"
                                        class="page-gallery-trigger"
                                        data-gallery-index="{{ $loop->index }}"
                                        data-gallery-src="{{ $imageUrl }}"
                                        data-gallery-alt="{{ $image->caption ?: $album->title }}"
                                        data-gallery-download="{{ $downloadName }}"
                                        aria-label="Open {{ $image->caption ?: $album->title }} image">
                                    <img src="{{ $imageUrl }}"
                                         alt="{{ $image->caption ?: $album->title }}" loading="lazy" decoding="async">
                                </button>
                                @if($image->caption)
                                    <figcaption>{{ $image->caption }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>

                    <div class="gallery-lightbox" id="eventGalleryLightbox" aria-hidden="true">
                        <button type="button" class="gallery-lightbox__backdrop" data-gallery-close aria-label="Close gallery preview"></button>
                        <div class="gallery-lightbox__stage" role="dialog" aria-modal="true" aria-label="{{ $album->title }} image preview">
                            <div class="gallery-lightbox__toolbar">
                                <span class="gallery-lightbox__counter" id="eventGalleryCounter"></span>
                                <div class="gallery-lightbox__actions">
                                    <a href="#" class="gallery-lightbox__button" id="eventGalleryDownload" download aria-label="Download current image">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                    <button type="button" class="gallery-lightbox__button" data-gallery-close aria-label="Close gallery preview">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--prev" data-gallery-prev aria-label="Previous image">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <img src="" alt="" class="gallery-lightbox__image" id="eventGalleryLightboxImage">
                            <button type="button" class="gallery-lightbox__nav gallery-lightbox__nav--next" data-gallery-next aria-label="Next image">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                @else
                    <p style="text-align:center;color:#64748b;padding:40px 0;">No photos in this album yet.</p>
                @endif
            </div>
        </section>
    </main>

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    <script>
        (() => {
            const lightbox = document.getElementById('eventGalleryLightbox');
            const image = document.getElementById('eventGalleryLightboxImage');
            const counter = document.getElementById('eventGalleryCounter');
            const download = document.getElementById('eventGalleryDownload');
            const triggers = Array.from(document.querySelectorAll('.page-gallery-trigger'));
            let currentIndex = 0;

            if (!lightbox || !image || !triggers.length) return;

            const render = (index) => {
                currentIndex = (index + triggers.length) % triggers.length;
                const item = triggers[currentIndex];
                const src = item.dataset.gallerySrc;

                image.src = src;
                image.alt = item.dataset.galleryAlt || '';
                download.href = src;
                download.setAttribute('download', item.dataset.galleryDownload || '');
                counter.textContent = `${currentIndex + 1} / ${triggers.length}`;
            };

            const open = (index) => {
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

            triggers.forEach((trigger, index) => {
                trigger.addEventListener('click', () => open(index));
            });

            lightbox.querySelectorAll('[data-gallery-close]').forEach((button) => {
                button.addEventListener('click', close);
            });

            lightbox.querySelector('[data-gallery-prev]')?.addEventListener('click', () => render(currentIndex - 1));
            lightbox.querySelector('[data-gallery-next]')?.addEventListener('click', () => render(currentIndex + 1));

            document.addEventListener('keydown', (event) => {
                if (!lightbox.classList.contains('is-open')) return;

                if (event.key === 'Escape') close();
                if (event.key === 'ArrowLeft') render(currentIndex - 1);
                if (event.key === 'ArrowRight') render(currentIndex + 1);
            });
        })();
    </script>
@endpush
