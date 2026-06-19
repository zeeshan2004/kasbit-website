@extends('layout.app')

@section('content')
    @include('frontend.partials.header')

    <?php $isProfilePage = str_contains(strtolower($page->slug), 'international-board-of-advisors'); ?>
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

        @if($page->pdf_file && in_array(strtolower($page->menu?->name ?? ''), ['fee structure', 'program profile', 'admission policy'], true))
            <section class="cms-pdf-download-section">
                <div class="container">
                    <div class="cms-pdf-download-card">
                        <span class="cms-pdf-download-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </span>
                        <div class="cms-pdf-download-copy">
                            <h2>{{ $page->title }}</h2>
                            <p>{{ $page->pdf_original_name ?: 'Download the PDF document.' }}</p>
                        </div>
                        <a href="{{ route('pages.pdf.download', $page) }}"
                           class="cms-pdf-download-button"
                           download>
                            <i class="fa-solid fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>
            </section>
        @endif

        @if($page->programSchemaTables->count())
            <section class="program-schema-section">
                <div class="container">
                    @foreach($page->programSchemaTables as $schemaTable)
                        <div class="program-schema-wrap">
                            <h2>{{ $schemaTable->title }}</h2>
                            <div class="program-schema-scroll">
                                <table class="program-schema-table">
                                    <thead>
                                        <tr>
                                            <th class="program-schema-number">#</th>
                                            <th>Subject</th>
                                            <th>Credit Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $serial = 0; ?>
                                        @foreach($schemaTable->rows as $row)
                                            <?php $serial++; ?>
                                            <tr class="{{ $row->is_total ? 'program-schema-total' : '' }}">
                                                <td class="program-schema-number">{{ $serial }}</td>
                                                <td>{{ $row->subject }}</td>
                                                <td>{{ $row->credit_hours }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    @include('frontend.partials.footer')
@endsection
