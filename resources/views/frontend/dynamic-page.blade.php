@extends('layout.app')

@section('content')
    @include('frontend.partials.header')

    <?php
        $isProfilePage = str_contains(strtolower($page->slug), 'international-board-of-advisors');
        $isMessagePage = str_contains(strtolower($page->slug), 'message')
            || str_contains(strtolower($page->slug), 'business-administration');
        $documentPageSlugs = ['quality-policy-statement', 'qec-structure', 'qec-activity-calendar', 'qec-activity-calender'];
        $isDocumentPage = in_array(strtolower($page->slug), $documentPageSlugs, true);
        $isQecActivitiesPage = strtolower($page->slug) === 'qec-activities';
    ?>
    <main class="cms-content-page {{ $isProfilePage ? 'cms-profile-page' : '' }} {{ $isMessagePage ? 'cms-message-page' : '' }} {{ $isDocumentPage ? 'cms-document-page' : '' }}" style="--page-accent:{{ $page->accent_color ?: '#07559d' }}">
        <section class="cms-content-hero">
            <div class="container">
                <span class="cms-content-eyebrow">{{ $page->eyebrow ?: $page->menu?->parent?->name }}</span>
                <h1>{{ $page->title }}</h1>
                @if($page->subtitle)
                    <p>{{ $page->subtitle }}</p>
                @endif
            </div>
        </section>

        @php
            $hasPageContent = $page->slides->count()
                || $page->programSchemaTables->count()
                || $page->academicCalendarTables->count()
                || $page->departments->count()
                || $page->galleryImages->count()
                || $page->eventAlbums->count()
                || $isQecActivitiesPage;
        @endphp

        @if($page->slides->count())
            <section class="cms-history-blocks {{ $isProfilePage ? 'cms-profile-blocks' : '' }}">
                <div class="container">
                    <div class="cms-history-list">
                        @foreach($page->slides as $slide)
                            @php
                                $hasSlideCopy = ! $isDocumentPage && (filled($slide->title) || filled($slide->description));
                                $slideImageUrl = $slide->image ? asset($slide->image) . '?v=' . $slide->updated_at?->timestamp : null;
                            @endphp
                            <article class="cms-history-item {{ $slide->image_position === 'right' ? 'cms-history-item--image-right' : '' }} {{ $isDocumentPage ? 'cms-document-item' : '' }}">
                                @if($slide->image)
                                    <div class="cms-history-image">
                                        @if($isDocumentPage)
                                            <button type="button"
                                                    class="cms-document-preview"
                                                    data-document-open
                                                    data-document-src="{{ $slideImageUrl }}"
                                                    data-document-title="{{ $page->title }}">
                                                <img src="{{ $slideImageUrl }}" alt="{{ $page->title }}" loading="lazy" decoding="async">
                                            </button>
                                            <div class="cms-document-actions">
                                                <button type="button"
                                                        class="cms-document-action"
                                                        data-document-open
                                                        data-document-src="{{ $slideImageUrl }}"
                                                        data-document-title="{{ $page->title }}">
                                                    <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                                                    Open
                                                </button>
                                                <a href="{{ $slideImageUrl }}"
                                                   class="cms-document-action"
                                                   download="{{ basename($slide->image) }}">
                                                    <i class="fa-solid fa-download"></i>
                                                    Download
                                                </a>
                                            </div>
                                        @else
                                            <img src="{{ $slideImageUrl }}" alt="{{ $slide->title }}" loading="lazy" decoding="async">
                                        @endif
                                    </div>
                                @endif
                                @if($hasSlideCopy)
                                    <div class="cms-history-copy">
                                        <span class="cms-history-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                        <h3>{{ $slide->title }}</h3>
                                        @if($slide->description)
                                            <p>{!! nl2br(e($slide->description)) !!}</p>
                                        @endif
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @elseif(! $hasPageContent)
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

        @if($page->programSchemaTables->count() && $isQecActivitiesPage)
            <section class="qec-activities-section">
                <div class="container">
                    @foreach($page->programSchemaTables as $schemaTable)
                        @php($hasFourthColumn = $schemaTable->rows->contains(fn ($row) => filled($row->col4_text)))
                        <div class="qec-table-wrap">
                            @if(strcasecmp($schemaTable->title, 'QEC Activities') !== 0)
                                <h2>{{ $schemaTable->title }}</h2>
                            @endif
                            <div class="qec-table-scroll">
                                <table class="qec-activities-table">
                                    <thead>
                                        <tr>
                                            <th>S. No</th>
                                            @if($hasFourthColumn)
                                                <th>Title of Workshop/Seminar</th>
                                                <th>Contributed by</th>
                                                <th>Venue</th>
                                                <th>Date Held</th>
                                            @else
                                                <th>Title of Event</th>
                                                <th>Date Held</th>
                                                <th>Host</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schemaTable->rows as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $row->subject }}</td>
                                                <td>{{ $row->credit_hours }}</td>
                                                <td>{{ $row->col3_text }}</td>
                                                @if($hasFourthColumn)
                                                    <td>{{ $row->col4_text }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @elseif($page->programSchemaTables->count())
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

        @if($page->academicCalendarTables->count())
            <section class="academic-calendar-section">
                <div class="container">
                    @foreach($page->academicCalendarTables as $calendarTable)
                        @if($calendarTable->type === 'note')
                            <div class="academic-calendar-note">
                                {{ $calendarTable->rows->first()?->occasion ?: $calendarTable->title }}
                            </div>
                            @continue
                        @endif

                        <div class="academic-calendar-wrap academic-calendar-wrap--{{ $calendarTable->type === 'holidays' ? 'holidays' : 'semester' }}">
                            @if($calendarTable->title)
                                <h2>{{ $calendarTable->title }}</h2>
                            @endif
                            <div class="academic-calendar-scroll">
                                <table class="academic-calendar-table">
                                    @if($calendarTable->type === 'holidays')
                                        <thead>
                                            <tr>
                                                <th>{{ $calendarTable->col1_label ?: 'Occasion' }}</th>
                                                <th>{{ $calendarTable->col2_label ?: 'Days' }}</th>
                                                <th>{{ $calendarTable->col3_label ?: 'Date' }}</th>
                                            </tr>
                                        </thead>
                                    @endif
                                    <tbody>
                                        @foreach($calendarTable->rows as $row)
                                            <tr>
                                                <td>{{ $row->occasion }}</td>
                                                @if($calendarTable->type === 'holidays')
                                                    <td>{{ $row->days }}</td>
                                                    <td>{{ $row->date_text }}</td>
                                                @else
                                                    <td>{{ $row->date_text }}</td>
                                                @endif
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

        @if($page->galleryImages->count())
            <section class="page-gallery-section">
                <div class="container">
                    <div class="page-gallery-grid">
                        @foreach($page->galleryImages as $galleryImage)
                            @php
                                $galleryImageUrl = asset($galleryImage->image) . '?v=' . $galleryImage->updated_at?->timestamp;
                                $galleryImageTitle = $galleryImage->caption ?: $page->title;
                            @endphp
                            <figure class="page-gallery-item {{ $isDocumentPage ? 'cms-document-gallery-item' : '' }}">
                                @if($isDocumentPage)
                                    <button type="button"
                                            class="cms-document-preview"
                                            data-document-open
                                            data-document-src="{{ $galleryImageUrl }}"
                                            data-document-title="{{ $galleryImageTitle }}">
                                        <img src="{{ $galleryImageUrl }}"
                                             alt="{{ $galleryImageTitle }}"
                                             loading="lazy" decoding="async">
                                    </button>
                                    <div class="cms-document-actions">
                                        <button type="button"
                                                class="cms-document-action"
                                                data-document-open
                                                data-document-src="{{ $galleryImageUrl }}"
                                                data-document-title="{{ $galleryImageTitle }}">
                                            <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                                            Open
                                        </button>
                                        <a href="{{ $galleryImageUrl }}"
                                           class="cms-document-action"
                                           download="{{ basename($galleryImage->image) }}">
                                            <i class="fa-solid fa-download"></i>
                                            Download
                                        </a>
                                    </div>
                                @else
                                    <img src="{{ $galleryImageUrl }}"
                                         alt="{{ $galleryImageTitle }}"
                                         loading="lazy" decoding="async">
                                @endif
                                @if($galleryImage->caption)
                                    <figcaption>{{ $galleryImage->caption }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if($page->eventAlbums->count())
            <section class="event-albums-section">
                <div class="container">
                    <div class="event-albums-grid">
                        @foreach($page->eventAlbums as $album)
                            <a href="{{ route('event-gallery.album', $album) }}" class="event-album-card">
                                <div class="event-album-cover">
                                    @if($album->cover_image)
                                        <img src="{{ asset($album->cover_image) }}?v={{ $album->updated_at?->timestamp }}"
                                             alt="{{ $album->title }}" loading="lazy" decoding="async">
                                    @else
                                        <span class="event-album-placeholder"><i class="fa-solid fa-photo-film"></i></span>
                                    @endif
                                    <span class="event-album-count"><i class="fa-solid fa-images"></i> {{ $album->images_count }}</span>
                                </div>
                                <div class="event-album-body">
                                    <h3>{{ $album->title }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if($page->departments->count())
            <section class="departments-section">
                <div class="container">
                    <div class="departments-grid">
                        @foreach($page->departments as $department)
                            @php
                                $deptTag = $department->link ? 'a' : 'div';
                            @endphp
                            <{{ $deptTag }} class="department-card" @if($department->link) href="{{ $department->link }}" @endif>
                                @if($department->image)
                                    <div class="department-card-image">
                                        <img src="{{ asset($department->image) }}?v={{ $department->updated_at?->timestamp }}" alt="{{ $department->name }}" loading="lazy" decoding="async">
                                    </div>
                                @endif
                                <div class="department-card-body">
                                    <h3>{{ $department->name }}</h3>
                                    @if($department->head_of_department)
                                        <p class="department-card-hod"><i class="fa-solid fa-user-tie"></i> {{ $department->head_of_department }}</p>
                                    @endif
                                    @if($department->description)
                                        <p class="department-card-text">{!! nl2br(e($department->description)) !!}</p>
                                    @endif
                                </div>
                            </{{ $deptTag }}>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @if($isDocumentPage)
        <div class="cms-document-modal" id="documentModal" aria-hidden="true">
            <div class="cms-document-modal__backdrop" data-document-close></div>
            <div class="cms-document-modal__dialog" role="dialog" aria-modal="true" aria-label="{{ $page->title }}">
                <div class="cms-document-modal__toolbar">
                    <strong id="documentModalTitle">{{ $page->title }}</strong>
                    <div class="cms-document-modal__actions">
                        <a href="#" class="cms-document-modal__button" id="documentModalDownload" download>
                            <i class="fa-solid fa-download"></i>
                            Download
                        </a>
                        <button type="button" class="cms-document-modal__button" data-document-close aria-label="Close document">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <div class="cms-document-modal__body">
                    <img src="" alt="{{ $page->title }}" id="documentModalImage">
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                (() => {
                    const modal = document.getElementById('documentModal');
                    const image = document.getElementById('documentModalImage');
                    const title = document.getElementById('documentModalTitle');
                    const download = document.getElementById('documentModalDownload');

                    if (!modal || !image || !title || !download) return;

                    const openModal = (trigger) => {
                        const src = trigger.dataset.documentSrc;
                        if (!src) return;

                        image.src = src;
                        title.textContent = trigger.dataset.documentTitle || '{{ $page->title }}';
                        download.href = src;
                        download.setAttribute('download', src.split('/').pop().split('?')[0] || 'document');
                        modal.classList.add('is-open');
                        modal.setAttribute('aria-hidden', 'false');
                        document.body.classList.add('document-modal-open');
                    };

                    const closeModal = () => {
                        modal.classList.remove('is-open');
                        modal.setAttribute('aria-hidden', 'true');
                        document.body.classList.remove('document-modal-open');
                    };

                    document.querySelectorAll('[data-document-open]').forEach((trigger) => {
                        trigger.addEventListener('click', () => openModal(trigger));
                    });

                    modal.querySelectorAll('[data-document-close]').forEach((trigger) => {
                        trigger.addEventListener('click', closeModal);
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
                    });
                })();
            </script>
        @endpush
    @endif

    @include('frontend.partials.footer')
@endsection
