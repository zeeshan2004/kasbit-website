@extends('layout.app')

@section('content')
    @include('frontend.partials.header')

    @php($isProfilePage = str_contains(strtolower($page->slug), 'international-board-of-advisors'))
    <main class="cms-content-page {{ $isProfilePage ? 'cms-profile-page' : '' }}" style="--page-accent:{{ $page->accent_color ?: '#07559d' }}">
        <section class="cms-content-hero">
            <div class="container">
                <span class="cms-content-eyebrow">{{ $page->eyebrow ?: $page->menu?->parent?->name }}</span>
                <h1>{{ $page->title }}</h1>
                @if($page->subtitle)
                    <p>{{ $page->subtitle }}</p>
                @endif
            </div>
        </section>

        @if($page->slides->count())
            <section class="cms-history-blocks {{ $isProfilePage ? 'cms-profile-blocks' : '' }}">
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
        @else
            <section class="cms-content-body">
                <div class="container">
                    <article class="cms-content-copy">
                        <p>Content will be available soon.</p>
                    </article>
                </div>
            </section>
        @endif
    </main>

    @include('frontend.partials.footer')
@endsection
