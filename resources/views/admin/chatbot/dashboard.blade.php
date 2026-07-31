<x-admin-layout>
    <x-slot name="title">AI Chatbot - KASBIT Control</x-slot>
    <x-slot name="header">AI Chatbot Management</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>Chatbot Overview</h3>
                <p>Monitor conversations, approved answers, provider status and questions requiring review.</p>
            </div>
            <span class="admin-status admin-status--{{ $provider ? 'active' : 'inactive' }}">
                {{ $provider ? $provider->name.' active' : 'No active provider' }}
            </span>
        </div>

        <div class="management-stats">
            <a href="{{ route('admin.chatbot.history.index') }}" class="management-stat management-stat--total">
                <span>Conversations</span><strong>{{ $stats['conversations'] }}</strong>
                <i class="fa-solid fa-comments"></i>
            </a>
            <a href="{{ route('admin.chatbot.history.index') }}" class="management-stat management-stat--progress">
                <span>Questions</span><strong>{{ $stats['questions'] }}</strong>
                <i class="fa-solid fa-message"></i>
            </a>
            <a href="{{ route('admin.chatbot.history.index', ['source' => 'knowledge_base']) }}" class="management-stat management-stat--resolved">
                <span>Local Answers</span><strong>{{ $stats['knowledge_answers'] }}</strong>
                <i class="fa-solid fa-database"></i>
            </a>
            <a href="{{ route('admin.chatbot.unanswered.index', ['status' => 'pending']) }}" class="management-stat management-stat--pending">
                <span>Needs Review</span><strong>{{ $stats['unanswered'] }}</strong>
                <i class="fa-solid fa-circle-question"></i>
            </a>
            <a href="{{ route('admin.chatbot.history.index', ['provider_id' => $provider?->id]) }}" class="management-stat management-stat--progress">
                <span>AI Answers</span><strong>{{ $stats['ai_answers'] }}</strong>
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </a>
            <div class="management-stat management-stat--total">
                <span>Average AI Time</span><strong>{{ $stats['average_ai_ms'] }} ms</strong>
                <i class="fa-solid fa-stopwatch"></i>
            </div>
            <a href="{{ route('admin.chatbot.providers.index') }}" class="management-stat management-stat--progress">
                <span>Active Provider</span><strong>{{ $provider?->name ?: 'None' }}</strong>
                <i class="fa-solid fa-microchip"></i>
            </a>
            <a href="{{ route('admin.chatbot.knowledge.index') }}" class="management-stat management-stat--resolved">
                <span>Knowledge Records</span><strong>{{ $stats['knowledge'] }}</strong>
                <i class="fa-solid fa-layer-group"></i>
            </a>
        </div>

        <div class="chatbot-admin-grid chatbot-admin-grid--2">
            <section class="management-panel">
                <div class="management-panel-header"><h4>Daily Questions · Last 7 Days</h4></div>
                @include('admin.chatbot.partials.bar-chart', ['chartData' => $daily])
            </section>

            <section class="management-panel">
                <div class="management-panel-header"><h4>Answer Sources</h4></div>
                @include('admin.chatbot.partials.bar-chart', ['chartData' => $sources])
            </section>

            <section class="management-panel">
                <div class="management-panel-header"><h4>AI Provider Usage</h4></div>
                @include('admin.chatbot.partials.bar-chart', ['chartData' => $providerUsage])
            </section>

            <section class="management-panel">
                <div class="management-panel-header"><h4>Popular Categories</h4></div>
                @include('admin.chatbot.partials.bar-chart', ['chartData' => $categoryUsage])
            </section>

            <section class="management-panel">
                <div class="management-panel-header">
                    <h4>Recent Unanswered</h4>
                    <a href="{{ route('admin.chatbot.unanswered.index') }}">View all</a>
                </div>
                <div class="chatbot-compact-list">
                    @forelse($recentUnanswered as $item)
                        <a href="{{ route('admin.chatbot.unanswered.index', ['search' => $item->user_question]) }}">
                            <span>{{ str($item->user_question)->limit(75) }}</span>
                            <small>{{ $item->asked_count }} ask(s) · {{ $item->last_asked_at?->diffForHumans() }}</small>
                        </a>
                    @empty
                        <p class="chatbot-muted">Nothing is waiting for review.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-admin-layout>
