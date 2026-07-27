@extends('layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/feedback.css') }}?v={{ filemtime(public_path('css/feedback.css')) }}">
@endpush

@section('content')
    @include('frontend.partials.header')

    <main class="feedback-page">
        <section class="feedback-page-header">
            <div class="feedback-inner feedback-page-header-inner">
                <div>
                    <span class="feedback-page-eyebrow">Student Feedback Portal</span>
                    <h1>Submit Your Query</h1>
                    <p>Select the relevant department and submit your question. Our team will review and respond to your query.</p>
                </div>
                <div class="feedback-account">
                    <strong>{{ $student->name }}</strong>
                    <span>{{ $student->student_id }} &middot; {{ $student->semester }}</span>
                </div>
            </div>
        </section>

        <section class="feedback-content">
            <div class="feedback-inner">
                @if(session('submitted_query_code'))
                    <div class="feedback-success" role="status">
                        <strong>Your query has been submitted successfully.</strong>
                        <span class="feedback-query-code">{{ session('submitted_query_code') }}</span>
                        <span>Please save this Query ID for future reference.</span>
                    </div>
                @elseif(session('success'))
                    <div class="frontend-alert frontend-alert--success" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="feedback-layout">
                    <section class="feedback-panel" aria-labelledby="feedback-form-title">
                        <h2 id="feedback-form-title">Query Details</h2>
                        <p class="feedback-panel-copy">Your account details are attached automatically to this query.</p>

                        @if($errors->any())
                            <div class="frontend-alert frontend-alert--error" role="alert">
                                Please correct the highlighted fields.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('feedback.store') }}" data-feedback-form>
                            @csrf

                            <div class="feedback-field-grid">
                                <div class="feedback-field">
                                    <label for="feedback-name">Full Name</label>
                                    <input id="feedback-name" type="text" value="{{ $student->name }}" readonly>
                                </div>

                                <div class="feedback-field">
                                    <label for="feedback-email">Email Address</label>
                                    <input id="feedback-email" type="email" value="{{ $student->email }}" readonly>
                                </div>

                                <div class="feedback-field feedback-field--full">
                                    <label for="feedback-department">Relevant Department</label>
                                    <select id="feedback-department" name="department_id" required>
                                        <option value="">Select department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}"
                                                    @selected((int) old('department_id') === $department->id)>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')<p class="field-error">{{ $message }}</p>@enderror
                                </div>

                                <div class="feedback-field feedback-field--full">
                                    <label for="feedback-message">Query / Message</label>
                                    <textarea id="feedback-message"
                                              name="message"
                                              minlength="10"
                                              maxlength="2000"
                                              required
                                              data-query-message>{{ old('message') }}</textarea>
                                    <div class="feedback-field-meta">
                                        <span>Minimum 10 characters</span>
                                        <span><strong data-character-count>0</strong> / 2000</span>
                                    </div>
                                    @error('message')<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="student-auth-actions">
                                <button type="submit" class="feedback-submit" data-submit-button>
                                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                                    <span>Submit Query</span>
                                </button>
                            </div>
                        </form>
                    </section>

                    <aside class="feedback-panel" aria-labelledby="feedback-history-title">
                        <h2 id="feedback-history-title">My Recent Queries</h2>
                        <p class="feedback-panel-copy">Latest submissions and their current review status.</p>

                        @if($studentQueries->count())
                            <div class="feedback-history-list">
                                @foreach($studentQueries as $studentQuery)
                                    <article class="feedback-history-item">
                                        <div class="feedback-history-top">
                                            <span class="feedback-history-code">{{ $studentQuery->query_code }}</span>
                                            <span class="query-status query-status--{{ $studentQuery->status }}">
                                                {{ $studentQuery->status_label }}
                                            </span>
                                        </div>
                                        <p>{{ str($studentQuery->message)->limit(92) }}</p>
                                        <small>{{ $studentQuery->department->name }} &middot; {{ $studentQuery->created_at->format('d M Y') }}</small>
                                        @if(filled($studentQuery->admin_notes))
                                            <div class="feedback-admin-response">
                                                <span>Admin Response</span>
                                                <p>{{ $studentQuery->admin_notes }}</p>
                                            </div>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <p class="feedback-empty">No queries submitted yet.</p>
                        @endif
                    </aside>
                </div>
            </div>
        </section>
    </main>

    @include('frontend.partials.footer')
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('[data-feedback-form]');
            const message = document.querySelector('[data-query-message]');
            const count = document.querySelector('[data-character-count]');

            const updateCount = () => {
                if (message && count) count.textContent = String(message.value.length);
            };

            message?.addEventListener('input', updateCount);
            updateCount();

            form?.addEventListener('submit', () => {
                const button = form.querySelector('[data-submit-button]');
                if (!button || button.disabled) return;

                button.disabled = true;
                button.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Submitting...</span>';
            });
        })();
    </script>
@endpush
