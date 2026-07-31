<x-admin-layout>
    <x-slot name="title">Chatbot History - KASBIT Control</x-slot>
    <x-slot name="header">Chatbot Conversation History</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>Question &amp; Answer History</h3>
                <p>Review saved conversations, response sources, providers and performance.</p>
            </div>
        </div>

        <section class="management-panel">
            <form method="GET" action="{{ route('admin.chatbot.history.index') }}">
                <div class="chatbot-filter-row">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search question or answer">
                    <input type="search" name="user" value="{{ request('user') }}" placeholder="User name, email or guest ID">
                    <select name="source">
                        <option value="">All sources</option>
                        @foreach($sources as $source)
                            <option value="{{ $source }}" @selected(request('source') === $source)>{{ str($source)->replace('_', ' ')->title() }}</option>
                        @endforeach
                    </select>
                    <select name="provider_id">
                        <option value="">All providers</option>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" @selected((int) request('provider_id') === $provider->id)>{{ $provider->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date" value="{{ request('date') }}">
                    <select name="category_id">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) request('category_id') === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="answer_status">
                        <option value="">Answered + unanswered</option>
                        <option value="answered" @selected(request('answer_status') === 'answered')>Answered only</option>
                        <option value="unanswered" @selected(request('answer_status') === 'unanswered')>Unanswered only</option>
                    </select>
                    <button class="admin-button admin-button--primary">Filter</button>
                    <a href="{{ route('admin.chatbot.history.index') }}" class="admin-button admin-button--secondary">Reset</a>
                </div>
            </form>
        </section>

        <section class="management-panel">
            <div class="management-panel-header">
                <h4>Saved Answers</h4>
                <span class="admin-status admin-status--student">{{ $messages->total() }} Results</span>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-data-table chatbot-history-table">
                    <thead>
                        <tr>
                            <th>Question / User</th>
                            <th>Answer</th>
                            <th>Source</th>
                            <th>Response</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>
                                    <strong>{{ str($message->parent?->content)->limit(110) }}</strong>
                                    @php($studentProfile = $message->conversation?->metadata['student_profile'] ?? null)
                                    <span class="admin-table-secondary">
                                        @if($studentProfile)
                                            {{ $studentProfile['full_name'] }} · ID: {{ $studentProfile['student_id'] }} · {{ $studentProfile['department_name'] }}
                                        @else
                                            {{ $message->conversation?->user?->email ?: 'Guest visitor' }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="chatbot-table-copy">{{ str($message->content)->limit(180) }}</span>
                                    <a href="{{ route('admin.chatbot.history.correct', $message) }}" class="admin-button admin-button--secondary" style="margin-top:.55rem;">
                                        <i class="fa-solid fa-pen-to-square"></i> Correct Answer
                                    </a>
                                </td>
                                <td>
                                    <span class="admin-status admin-status--{{ $message->answer_source === 'unanswered' ? 'pending' : 'active' }}">
                                        {{ str($message->answer_source ?: 'unknown')->replace('_', ' ')->title() }}
                                    </span>
                                    @if($message->provider)<span class="admin-table-secondary">{{ $message->provider->name }}</span>@endif
                                </td>
                                <td>{{ $message->response_time_ms ? $message->response_time_ms.' ms' : '—' }}</td>
                                <td>
                                    {{ $message->created_at?->format('d M Y') }}
                                    <span class="admin-table-secondary">{{ $message->created_at?->format('h:i A') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="admin-empty-state">No saved chatbot answers match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('admin.partials.pagination', ['paginator' => $messages])
        </section>
    </div>
</x-admin-layout>
