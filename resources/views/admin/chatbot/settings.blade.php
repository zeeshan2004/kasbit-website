<x-admin-layout>
    <x-slot name="title">Chatbot Settings - KASBIT Control</x-slot>
    <x-slot name="header">AI Chatbot Settings</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>General &amp; Security Settings</h3>
                <p>Configure the visitor experience, limits, privacy text and protected system instructions.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.chatbot.settings.update') }}">
            @csrf @method('PUT')
            <section class="management-panel">
                <div class="management-panel-header"><h4>Appearance &amp; Messages</h4></div>
                <div class="chatbot-form-grid">
                    <label>Chatbot Name<input name="chatbot_name" value="{{ old('chatbot_name', $settings->chatbot_name) }}" required></label>
                    <label>Header Title<input name="header_title" value="{{ old('header_title', $settings->header_title) }}" required></label>
                    <label>Icon Class<input name="chatbot_icon" value="{{ old('chatbot_icon', $settings->chatbot_icon) }}" required></label>
                    <label>Primary Color<input type="color" name="primary_color" value="{{ old('primary_color', $settings->primary_color) }}" required></label>
                    <label class="chatbot-span-2">Welcome Message<textarea name="welcome_message" rows="3" required>{{ old('welcome_message', $settings->welcome_message) }}</textarea></label>
                    <label class="chatbot-span-2">Input Placeholder<input name="placeholder_text" value="{{ old('placeholder_text', $settings->placeholder_text) }}" required></label>
                    <label>Default Error Message<textarea name="default_error_message" rows="4" required>{{ old('default_error_message', $settings->default_error_message) }}</textarea></label>
                    <label>No Confirmed Answer Message<textarea name="no_answer_message" rows="4" required>{{ old('no_answer_message', $settings->no_answer_message) }}</textarea></label>
                    <label class="chatbot-span-2">Privacy Message<textarea name="privacy_message" rows="3">{{ old('privacy_message', $settings->privacy_message) }}</textarea></label>
                </div>
            </section>

            <section class="management-panel">
                <div class="management-panel-header"><h4>Availability &amp; Limits</h4></div>
                <div class="chatbot-checks chatbot-checks--cards">
                    @foreach([
                        'is_enabled' => ['Enable Chatbot', 'Show the widget on the public website.'],
                        'guest_chat_enabled' => ['Allow Guests', 'Visitors can chat without signing in.'],
                        'save_history' => ['Save History', 'Store conversations for administrator review.'],
                        'suggestions_enabled' => ['Show Suggestions', 'Display welcome question shortcuts.'],
                        'ai_fallback_enabled' => ['AI Fallback', 'Use the active provider after local sources.'],
                    ] as $field => [$label, $help])
                        <label>
                            <input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $settings->{$field}))>
                            <span><strong>{{ $label }}</strong><small>{{ $help }}</small></span>
                        </label>
                    @endforeach
                </div>
                <div class="chatbot-form-grid">
                    <label>Questions Per Minute<input type="number" name="max_questions_per_minute" value="{{ old('max_questions_per_minute', $settings->max_questions_per_minute) }}" min="1" max="120" required></label>
                    <label>Maximum Message Length<input type="number" name="max_message_length" value="{{ old('max_message_length', $settings->max_message_length) }}" min="50" max="2000" required></label>
                </div>
            </section>

            <section class="management-panel">
                <div class="management-panel-header"><h4>Protected System Instructions</h4></div>
                <p class="chatbot-muted">These instructions are sent server-side and are never exposed to the browser.</p>
                <div class="chatbot-form-stack">
                    <label>System Prompt<textarea name="system_prompt" rows="10" required>{{ old('system_prompt', $settings->system_prompt) }}</textarea></label>
                </div>
            </section>

            <button class="admin-button admin-button--primary chatbot-save-button"><i class="fa-solid fa-floppy-disk"></i> Save Chatbot Settings</button>
        </form>
    </div>
</x-admin-layout>
