@extends('layout.app')

@section('content')
    @include('frontend.partials.header')

    <main id="about-us" class="about-history-page">
        <section class="cms-content-hero" style="--page-accent:{{ $page?->accent_color ?: '#07559d' }}">
            <div class="container">
                <span class="cms-content-eyebrow">{{ $page?->eyebrow ?: 'ABOUT KASBIT' }}</span>
                <h1>{{ $page?->title ?: 'About Us' }}</h1>
                @if($page?->subtitle)
                    <p>{{ $page->subtitle }}</p>
                @endif
            </div>
        </section>

        @if($page?->slides?->count())
            <section class="cms-history-blocks about-page-sections" style="--page-accent:{{ $page->accent_color ?: '#07559d' }}">
                <div class="container">
                    <div class="cms-history-list">
                        @foreach($page->slides as $slide)
                            <article class="cms-history-item {{ $slide->image_position === 'right' ? 'cms-history-item--image-right' : '' }}">
                                @if($slide->image)
                                    <div class="cms-history-image">
                                        <img src="{{ asset($slide->image) }}?v={{ $slide->updated_at?->timestamp }}" alt="{{ $slide->title }}" loading="lazy">
                                    </div>
                                @endif
                                <div class="cms-history-copy">
                                    <span class="cms-history-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3>{{ $slide->title }}</h3>
                                    @if($slide->description)
                                        <p>{!! nl2br(e($slide->description)) !!}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('frontend.partials.footer')
@endsection
