<x-admin-layout>
    <x-slot name="title">Chatbot Suggestions - KASBIT Control</x-slot>
    <x-slot name="header">Chatbot Suggested Questions</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>Suggested Questions</h3>
                <p>Control welcome shortcuts and optional fixed administrator answers.</p>
            </div>
        </div>

        <details class="management-panel chatbot-disclosure" open>
            <summary><i class="fa-solid fa-plus"></i> Add Suggestion</summary>
            <form method="POST" action="{{ route('admin.chatbot.suggestions.store') }}">
                @csrf
                @include('admin.chatbot.partials.suggestion-fields', ['suggestion' => null])
                <button class="admin-button admin-button--primary"><i class="fa-solid fa-plus"></i> Add Suggestion</button>
            </form>
        </details>

        <div class="chatbot-card-list">
            @foreach($suggestions as $suggestion)
                <details class="management-panel chatbot-disclosure chatbot-entry">
                    <summary>
                        <span>
                            <strong>{{ $suggestion->question }}</strong>
                            <small>{{ $suggestion->category?->name ?: 'All categories' }} · Order {{ $suggestion->display_order }}</small>
                        </span>
                        <span class="admin-status admin-status--{{ $suggestion->is_active ? 'active' : 'inactive' }}">{{ $suggestion->is_active ? 'Active' : 'Inactive' }}</span>
                    </summary>
                    <form method="POST" action="{{ route('admin.chatbot.suggestions.update', $suggestion) }}">
                        @csrf @method('PUT')
                        @include('admin.chatbot.partials.suggestion-fields', ['suggestion' => $suggestion])
                        <button class="admin-button admin-button--primary"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                    </form>
                    <form method="POST" action="{{ route('admin.chatbot.suggestions.destroy', $suggestion) }}" data-confirm-message="Delete this suggestion?">
                        @csrf @method('DELETE')
                        <button class="admin-button admin-button--danger"><i class="fa-solid fa-trash"></i> Delete</button>
                    </form>
                </details>
            @endforeach
        </div>
    </div>
</x-admin-layout>
