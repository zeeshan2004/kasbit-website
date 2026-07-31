<x-admin-layout>
    <x-slot name="title">Unanswered Questions - KASBIT Control</x-slot>
    <x-slot name="header">Unanswered Chatbot Questions</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>Needs Administrator Review</h3>
                <p>Answer recurring questions and promote verified information into the knowledge base.</p>
            </div>
        </div>

        <section class="management-panel">
            <form method="GET" action="{{ route('admin.chatbot.unanswered.index') }}">
                <div class="chatbot-filter-row">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search questions">
                    <select name="status">
                        <option value="">All statuses</option>
                        @foreach(['pending', 'answered', 'ignored'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->title() }}</option>
                        @endforeach
                    </select>
                    <button class="admin-button admin-button--primary">Filter</button>
                    <a href="{{ route('admin.chatbot.unanswered.index') }}" class="admin-button admin-button--secondary">Reset</a>
                </div>
            </form>
        </section>

        <div class="chatbot-card-list">
            @forelse($items as $item)
                <details class="management-panel chatbot-disclosure chatbot-entry">
                    <summary>
                        <span>
                            <strong>{{ $item->user_question }}</strong>
                            @if($item->student_name || $item->student_id || $item->department)
                                <small>
                                    {{ $item->student_name ?: 'Unknown student' }} ·
                                    ID: {{ $item->student_id ?: '—' }} ·
                                    {{ $item->department?->name ?: 'No department' }}
                                </small>
                            @endif
                            <small>Asked {{ $item->asked_count }} time(s) · {{ $item->last_asked_at?->format('d M Y, h:i A') }}</small>
                        </span>
                        <span class="admin-status admin-status--{{ $item->status === 'answered' ? 'active' : ($item->status === 'pending' ? 'pending' : 'inactive') }}">{{ str($item->status)->title() }}</span>
                    </summary>

                    @if($item->ai_response)
                        <div class="chatbot-provider-error"><strong>Provider detail:</strong> {{ $item->ai_response }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.chatbot.unanswered.update', $item) }}">
                        @csrf @method('PUT')
                        <div class="chatbot-form-grid">
                            <label class="chatbot-span-2">Administrator Answer<textarea name="admin_answer" rows="5">{{ $item->admin_answer }}</textarea></label>
                            <label>Status
                                <select name="status">
                                    @foreach(['pending', 'answered', 'ignored'] as $status)
                                        <option value="{{ $status }}" @selected($item->status === $status)>{{ str($status)->title() }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>Internal Notes<textarea name="notes" rows="3">{{ $item->notes }}</textarea></label>
                        </div>
                        <button class="admin-button admin-button--secondary"><i class="fa-solid fa-floppy-disk"></i> Save Review</button>
                    </form>

                    <form method="POST" action="{{ route('admin.chatbot.unanswered.promote', $item) }}" class="chatbot-promote-form">
                        @csrf
                        <h5>Approve and add to knowledge base</h5>
                        <div class="chatbot-form-grid">
                            <label>Category
                                <select name="category_id">
                                    <option value="">Uncategorized</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>Priority<input type="number" name="priority" min="0" max="1000" value="0"></label>
                            <label class="chatbot-span-2">Approved Answer<textarea name="answer" rows="5" required>{{ $item->admin_answer }}</textarea></label>
                            <label>Alternative Questions <small>One per line</small><textarea name="alternatives" rows="4"></textarea></label>
                            <label>Related Questions <small>One per line</small><textarea name="related_questions" rows="4"></textarea></label>
                        </div>
                        <button class="admin-button admin-button--primary"><i class="fa-solid fa-book-circle-check"></i> Promote to Knowledge Base</button>
                    </form>
                </details>
            @empty
                <section class="management-panel"><p class="chatbot-muted">No unanswered questions match these filters.</p></section>
            @endforelse
        </div>
        @include('admin.partials.pagination', ['paginator' => $items])
    </div>
</x-admin-layout>
