<x-admin-layout>
    <x-slot name="title">Chatbot Knowledge - KASBIT Control</x-slot>
    <x-slot name="header">Chatbot Knowledge Base</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>Approved Knowledge</h3>
                <p>These answers take priority over website search and external AI.</p>
            </div>
        </div>

        <div class="chatbot-admin-grid chatbot-admin-grid--knowledge">
            <aside>
                <section class="management-panel">
                    <div class="management-panel-header"><h4>Categories</h4></div>
                    <form method="POST" action="{{ route('admin.chatbot.categories.store') }}">
                        @csrf
                        <div class="chatbot-form-stack">
                            <label>Name<input name="name" required></label>
                            <label>Description<textarea name="description" rows="2"></textarea></label>
                            <label>Order<input type="number" name="sort_order" value="0" min="0"></label>
                        </div>
                        <button class="admin-button admin-button--primary"><i class="fa-solid fa-plus"></i> Add Category</button>
                    </form>
                    <div class="chatbot-category-list">
                        @foreach($categories as $category)
                            <div>
                                <span>{{ $category->name }} <small>{{ $category->knowledge_count }}</small></span>
                                <form method="POST" action="{{ route('admin.chatbot.categories.destroy', $category) }}" data-confirm-message="Remove this category? Knowledge answers will be kept.">
                                    @csrf @method('DELETE')
                                    <button title="Remove category"><i class="fa-solid fa-xmark"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>
            </aside>

            <div>
                <details class="management-panel chatbot-disclosure" open>
                    <summary><i class="fa-solid fa-plus"></i> Add Knowledge Answer</summary>
                    <form method="POST" action="{{ route('admin.chatbot.knowledge.store') }}">
                        @csrf
                        @include('admin.chatbot.partials.knowledge-fields', ['item' => null])
                        <button class="admin-button admin-button--primary"><i class="fa-solid fa-floppy-disk"></i> Save Answer</button>
                    </form>
                </details>

                {{-- Data Import Section --}}
                <details class="management-panel chatbot-disclosure">
                    <summary><i class="fa-solid fa-file-import"></i> Import Data Files</summary>
                    <div class="chatbot-import-section">
                        <h5>Upload Knowledge Data</h5>
                        <p>Upload any data files (.csv, .txt). The AI will read this data and use it to answer user questions automatically. No specific format needed — just raw data.</p>
                        <form method="POST" action="{{ route('admin.chatbot.knowledge.import') }}" enctype="multipart/form-data" id="csv-import-form">
                            @csrf
                            <div class="chatbot-form-stack">
                                <label>Data Files
                                    <input type="file" name="files[]" accept=".csv,.txt,.md" multiple required id="csv-file-input">
                                </label>
                            </div>
                            <div class="chatbot-file-list" id="csv-file-list" hidden></div>
                            @if($errors->has('files') || $errors->has('files.*'))
                                <div class="chatbot-import-result chatbot-import-result--error" style="margin-top:.5rem;">
                                    {{ $errors->first('files') ?: $errors->first('files.*') }}
                                </div>
                            @endif
                            <button class="admin-button admin-button--primary" style="margin-top:.6rem;"><i class="fa-solid fa-upload"></i> Import</button>
                        </form>

                        @php
                            $documents = \App\Models\ChatbotDocument::where('is_active', true)->latest()->get();
                        @endphp
                        @if($documents->count() > 0)
                            <div class="chatbot-import-result chatbot-import-result--success" style="margin-top:.8rem;">
                                <strong><i class="fa-solid fa-check-circle"></i> {{ $documents->count() }} file(s)</strong> uploaded. AI is using this data.
                            </div>
                            <div class="chatbot-file-list" style="margin-top:.5rem;">
                                @foreach($documents as $doc)
                                    <div class="chatbot-file-item">
                                        <i class="fa-solid fa-file-lines"></i>
                                        <span>{{ $doc->original_name }} <small>({{ number_format($doc->content_length / 1024, 1) }} KB · {{ $doc->created_at->diffForHumans() }})</small></span>
                                        <form method="POST" action="{{ route('admin.chatbot.knowledge.import.delete', $doc) }}" style="margin:0;" data-confirm-message="Remove this file?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="chatbot-file-remove" title="Remove"><i class="fa-solid fa-xmark"></i></button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </details>

                <section class="management-panel">
                    <form method="GET" action="{{ route('admin.chatbot.knowledge.index') }}">
                        <div class="chatbot-filter-row">
                            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search question or answer">
                            <select name="category_id">
                                <option value="">All categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) request('category_id') === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <select name="status">
                                <option value="">All statuses</option>
                                @foreach(['approved', 'draft', 'disabled'] as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->title() }}</option>
                                @endforeach
                            </select>
                            <button class="admin-button admin-button--primary">Filter</button>
                            <a href="{{ route('admin.chatbot.knowledge.index') }}" class="admin-button admin-button--secondary">Reset</a>
                        </div>
                    </form>
                </section>

                <div class="chatbot-card-list">
                    @forelse($knowledgeItems as $item)
                        <details class="management-panel chatbot-disclosure chatbot-entry">
                            <summary>
                                <span>
                                    <strong>{{ $item->question }}</strong>
                                    <small>{{ $item->category?->name ?: 'Uncategorized' }} · Priority {{ $item->priority }}</small>
                                </span>
                                <span class="admin-status admin-status--{{ $item->status === 'approved' ? 'active' : 'inactive' }}">{{ str($item->status)->title() }}</span>
                            </summary>
                            <form method="POST" action="{{ route('admin.chatbot.knowledge.update', $item) }}">
                                @csrf @method('PUT')
                                @include('admin.chatbot.partials.knowledge-fields', ['item' => $item])
                                <button class="admin-button admin-button--primary"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                            </form>
                            <form method="POST" action="{{ route('admin.chatbot.knowledge.destroy', $item) }}" data-confirm-message="Delete this knowledge answer?">
                                @csrf @method('DELETE')
                                <button class="admin-button admin-button--danger"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </details>
                    @empty
                        <section class="management-panel"><p class="chatbot-muted">No knowledge entries match these filters.</p></section>
                    @endforelse
                </div>
                @include('admin.partials.pagination', ['paginator' => $knowledgeItems])
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    (() => {
        const input = document.getElementById('csv-file-input');
        const list = document.getElementById('csv-file-list');
        if (!input || !list) return;

        const renderList = () => {
            const dt = input.files;
            if (!dt || dt.length === 0) {
                list.hidden = true;
                list.innerHTML = '';
                return;
            }

            list.hidden = false;
            list.innerHTML = '';

            for (let i = 0; i < dt.length; i++) {
                const file = dt[i];
                const item = document.createElement('div');
                item.className = 'chatbot-file-item';
                item.innerHTML = '<i class="fa-solid fa-file-csv"></i> '
                    + '<span>' + file.name + ' <small>(' + (file.size / 1024).toFixed(1) + ' KB)</small></span>'
                    + '<button type="button" class="chatbot-file-remove" title="Remove" data-index="' + i + '"><i class="fa-solid fa-xmark"></i></button>';
                list.appendChild(item);
            }
        };

        input.addEventListener('change', renderList);

        list.addEventListener('click', (e) => {
            const btn = e.target.closest('.chatbot-file-remove');
            if (!btn) return;

            const index = parseInt(btn.dataset.index);
            const dt = new DataTransfer();
            for (let i = 0; i < input.files.length; i++) {
                if (i !== index) dt.items.add(input.files[i]);
            }
            input.files = dt.files;
            renderList();
        });
    })();
    </script>
    @endpush
</x-admin-layout>
