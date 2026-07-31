<x-admin-layout>
    <x-slot name="title">AI Providers - KASBIT Control</x-slot>
    <x-slot name="header">AI Chatbot Providers</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>AI Providers</h3>
                <p>Configure the AI provider and optional live knowledge sources. API key values remain in your <code>.env</code> file.</p>
            </div>
        </div>

        <details class="management-panel chatbot-disclosure" @if($errors->any()) open @endif>
            <summary><i class="fa-solid fa-plus"></i> Add Provider</summary>
            <form method="POST" action="{{ route('admin.chatbot.providers.store') }}" id="ai-provider-create-form">
                @csrf
                <div class="chatbot-form-grid">
                    <label>Name<input name="name" value="{{ old('name') }}" required></label>
                    <label>Type
                        <select name="type" required data-provider-type>
                            @foreach(['openai' => 'OpenAI', 'openrouter' => 'OpenRouter', 'claude' => 'Anthropic Claude', 'gemini' => 'Google Gemini', 'custom' => 'Custom API'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>Model<input name="model" value="{{ old('model', 'gpt-5.6-sol') }}" data-provider-field="model" required></label>
                    <label>API Key Environment Variable<input name="api_key_env" value="{{ old('api_key_env', 'OPENAI_API_KEY') }}" data-provider-field="api_key_env" required></label>
                    <label class="chatbot-span-2">HTTPS Endpoint<input type="url" name="endpoint" value="{{ old('endpoint', 'https://api.openai.com/v1/responses') }}" data-provider-field="endpoint"></label>
                    <label>Temperature<input type="number" name="temperature" value="{{ old('temperature') }}" min="0" max="2" step="0.01"></label>
                    <label>Maximum Output Tokens<input type="number" name="max_tokens" value="{{ old('max_tokens', 1200) }}" min="64" max="32000" required></label>
                    <label class="chatbot-span-2">Provider Instructions (optional)<textarea name="system_prompt" rows="3">{{ old('system_prompt') }}</textarea></label>
                    <label class="chatbot-span-2">Knowledge Source URL <small>Public webpage the assistant should read before answering.</small><input type="url" name="knowledge_source_url" value="{{ old('knowledge_source_url') }}" placeholder="https://www.example.edu/admissions"></label>
                    <label class="chatbot-span-2">Knowledge API URL <small>A GET request is sent with the visitor question in the <code>question</code> query parameter.</small><input type="url" name="knowledge_api_url" value="{{ old('knowledge_api_url') }}" placeholder="https://api.example.edu/knowledge"></label>
                    <label class="chatbot-span-2">Knowledge API Key Environment Variable <small>Optional bearer token; keep the actual secret in <code>.env</code>.</small><input name="knowledge_api_key_env" value="{{ old('knowledge_api_key_env') }}" placeholder="KNOWLEDGE_API_KEY"></label>
                </div>
                <div class="chatbot-checks">
                    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active'))> Active</label>
                    <label><input type="checkbox" name="is_default" value="1" @checked(old('is_default'))> Default provider</label>
                </div>
                <button class="admin-button admin-button--primary"><i class="fa-solid fa-floppy-disk"></i> Add Provider</button>
            </form>
        </details>

        <div class="chatbot-card-list">
            @foreach($providers as $provider)
                <article class="management-panel chatbot-provider-card">
                    <div class="management-panel-header">
                        <div>
                            <h4>{{ $provider->name }}</h4>
                            <p>{{ str($provider->type)->title() }} · {{ $provider->model }}</p>
                        </div>
                        <div class="chatbot-badges">
                            @if($provider->is_default)<span class="admin-status admin-status--student">Default</span>@endif
                            <span class="admin-status admin-status--{{ $provider->is_active ? 'active' : 'inactive' }}">{{ $provider->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.chatbot.providers.update', $provider) }}">
                        @csrf @method('PUT')
                        <div class="chatbot-form-grid">
                            <label>Name<input name="name" value="{{ $provider->name }}" required></label>
                            <label>Type
                                <select name="type">
                                    @foreach(['openai' => 'OpenAI', 'openrouter' => 'OpenRouter', 'claude' => 'Anthropic Claude', 'gemini' => 'Google Gemini', 'custom' => 'Custom API'] as $type => $label)
                                        <option value="{{ $type }}" @selected($provider->type === $type)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>Model<input name="model" value="{{ $provider->model }}" required></label>
                            <label>API Key Env<input name="api_key_env" value="{{ $provider->api_key_env }}" required></label>
                            <label class="chatbot-span-2">Endpoint<input type="url" name="endpoint" value="{{ $provider->endpoint }}"></label>
                            <label>Temperature<input type="number" name="temperature" value="{{ $provider->temperature }}" min="0" max="2" step=".01"></label>
                            <label>Max Tokens<input type="number" name="max_tokens" value="{{ $provider->max_tokens }}" min="64" max="32000"></label>
                            <label class="chatbot-span-2">Provider Instructions<textarea name="system_prompt" rows="3">{{ $provider->system_prompt }}</textarea></label>
                            <label class="chatbot-span-2">Knowledge Source URL <small>Public webpage the assistant reads as live reference data.</small><input type="url" name="knowledge_source_url" value="{{ $provider->knowledge_source_url }}" placeholder="https://www.example.edu/admissions"></label>
                            <label class="chatbot-span-2">Knowledge API URL <small>Receives a GET request with <code>question=&lt;visitor question&gt;</code>.</small><input type="url" name="knowledge_api_url" value="{{ $provider->knowledge_api_url }}" placeholder="https://api.example.edu/knowledge"></label>
                            <label class="chatbot-span-2">Knowledge API Key Environment Variable <small>Optional bearer token stored in <code>.env</code>, not in the database.</small><input name="knowledge_api_key_env" value="{{ $provider->knowledge_api_key_env }}" placeholder="KNOWLEDGE_API_KEY"></label>
                        </div>
                        <div class="chatbot-checks">
                            <label><input type="checkbox" name="is_active" value="1" @checked($provider->is_active)> Active</label>
                            <label><input type="checkbox" name="is_default" value="1" @checked($provider->is_default)> Default</label>
                        </div>
                        <button class="admin-button admin-button--primary"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                    </form>

                    <div class="chatbot-inline-actions">
                        <form method="POST" action="{{ route('admin.chatbot.providers.test', $provider) }}">
                            @csrf
                            <button class="admin-button admin-button--secondary"><i class="fa-solid fa-plug-circle-check"></i> Test Connection</button>
                        </form>
                        @unless($provider->is_default)
                            <form method="POST" action="{{ route('admin.chatbot.providers.default', $provider) }}">
                                @csrf
                                <button class="admin-button admin-button--secondary">Make Default</button>
                            </form>
                        @endunless
                        <form method="POST" action="{{ route('admin.chatbot.providers.destroy', $provider) }}" data-confirm-message="Remove {{ $provider->name }}?">
                            @csrf @method('DELETE')
                            <button class="admin-button admin-button--danger"><i class="fa-solid fa-trash"></i> Remove</button>
                        </form>
                    </div>
                    @if($provider->last_tested_at)
                        <p class="chatbot-test-result">Last test: {{ $provider->last_test_status }} · {{ $provider->last_tested_at->diffForHumans() }} — {{ str($provider->last_test_message)->limit(160) }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const form = document.getElementById('ai-provider-create-form');
                const type = form?.querySelector('[data-provider-type]');
                if (!form || !type) return;

                const presets = {
                    openai: {
                        model: 'gpt-5.6-sol',
                        api_key_env: 'OPENAI_API_KEY',
                        endpoint: 'https://api.openai.com/v1/responses',
                    },
                    openrouter: {
                        model: 'openai/gpt-4o',
                        api_key_env: 'OPENROUTER_API_KEY',
                        endpoint: 'https://openrouter.ai/api/v1/chat/completions',
                    },
                    claude: {
                        model: 'claude-sonnet-4-5',
                        api_key_env: 'ANTHROPIC_API_KEY',
                        endpoint: 'https://api.anthropic.com/v1/messages',
                    },
                    gemini: {
                        model: 'gemini-2.5-flash',
                        api_key_env: 'GEMINI_API_KEY',
                        endpoint: 'https://generativelanguage.googleapis.com/v1beta/models',
                    },
                    custom: {
                        model: '',
                        api_key_env: 'CUSTOM_AI_API_KEY',
                        endpoint: '',
                    },
                };

                const knownValues = Object.values(presets).reduce((values, preset) => {
                    Object.entries(preset).forEach(([field, value]) => values[field].add(value));
                    return values;
                }, { model: new Set(), api_key_env: new Set(), endpoint: new Set() });

                const applyPreset = () => {
                    const preset = presets[type.value];
                    if (!preset) return;

                    Object.entries(preset).forEach(([name, value]) => {
                        const input = form.querySelector(`[data-provider-field="${name}"]`);
                        if (input && (!input.value || knownValues[name].has(input.value))) {
                            input.value = value;
                        }
                    });
                };

                type.addEventListener('change', applyPreset);
                applyPreset();
            })();
        </script>
    @endpush
</x-admin-layout>
