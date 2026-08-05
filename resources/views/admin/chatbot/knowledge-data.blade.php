<x-admin-layout>
    <x-slot name="title">Knowledge Data - KASBIT Control</x-slot>
    <x-slot name="header">Chatbot Knowledge Data</x-slot>

    <div class="management-page chatbot-admin">
        @include('admin.chatbot.partials.alerts')
        @include('admin.chatbot.partials.nav')

        <div class="management-heading">
            <div>
                <h3>Knowledge Data</h3>
                <p>Add data by category. AI will search this data and generate polished answers automatically.</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="chatbot-checks chatbot-checks--cards" style="margin-bottom:1rem;">
            @foreach($intents as $key => $label)
                <div class="chatbot-stat-card">
                    <strong>{{ $counts[$key] ?? 0 }}</strong>
                    <small>{{ $label }}</small>
                </div>
            @endforeach
        </div>

        {{-- Import Files --}}
        <details class="management-panel chatbot-disclosure">
            <summary><i class="fa-solid fa-file-import"></i> Import Data Files</summary>
            <div class="chatbot-import-section">
                <p>Upload CSV or text files. CSV with column headers (Name, Qualification, etc.) will be auto-parsed into separate entries. Plain text will be stored as-is.</p>
                <form method="POST" action="{{ route('admin.chatbot.data.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="chatbot-form-grid">
                        <label>Category (required)
                            <select name="import_intent" required>
                                @foreach($intents as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>Files (.csv, .txt)
                            <input type="file" name="files[]" accept=".csv,.txt,.md" multiple required>
                        </label>
                    </div>
                    <button class="admin-button admin-button--primary" style="margin-top:.6rem;"><i class="fa-solid fa-upload"></i> Import</button>
                </form>
            </div>
        </details>

        {{-- Add Manual Entry --}}
        <details class="management-panel chatbot-disclosure" @if($errors->any()) open @endif>
            <summary><i class="fa-solid fa-plus"></i> Add Entry Manually</summary>
            <form method="POST" action="{{ route('admin.chatbot.data.store') }}">
                @csrf
                <div class="chatbot-form-grid">
                    <label>Category
                        <select name="intent" required>
                            @foreach($intents as $key => $label)
                                <option value="{{ $key }}" @selected(old('intent') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>Title (person name, program name, etc.)
                        <input name="title" value="{{ old('title') }}" required placeholder="e.g. Dr. Basheer, BBA Fee, BSCS Program">
                    </label>
                    <label class="chatbot-span-2">Content (all info about this topic)
                        <textarea name="content" rows="6" required placeholder="Designation: Senior Lecturer&#10;Department: Computer Science&#10;Courses: Database, Web Tech, Programming&#10;Email: basheer@kasbit.edu.pk">{{ old('content') }}</textarea>
                    </label>
                    <label class="chatbot-span-2">Search Keywords (optional, comma separated)
                        <input name="keywords" value="{{ old('keywords') }}" placeholder="basheer, database, web technology">
                    </label>
                </div>
                <button class="admin-button admin-button--primary" style="margin-top:.6rem;"><i class="fa-solid fa-floppy-disk"></i> Save</button>
            </form>
        </details>

        {{-- Filter --}}
        <section class="management-panel">
            <form method="GET" action="{{ route('admin.chatbot.data.index') }}">
                <div class="chatbot-filter-row">
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search...">
                    <select name="intent">
                        <option value="">All categories</option>
                        @foreach($intents as $key => $label)
                            <option value="{{ $key }}" @selected(request('intent') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="admin-button admin-button--primary">Filter</button>
                    <a href="{{ route('admin.chatbot.data.index') }}" class="admin-button admin-button--secondary">Reset</a>
                </div>
            </form>
        </section>

        {{-- List --}}
        <div class="chatbot-card-list">
            @forelse($items as $item)
                <details class="management-panel chatbot-disclosure chatbot-entry">
                    <summary>
                        <span>
                            <strong>{{ $item->title }}</strong>
                            <small><span class="admin-status admin-status--{{ $item->is_active ? 'active' : 'inactive' }}">{{ $intents[$item->intent] ?? $item->intent }}</span> · {{ Str::limit($item->content, 80) }}</small>
                        </span>
                    </summary>
                    <form method="POST" action="{{ route('admin.chatbot.data.update', $item) }}">
                        @csrf @method('PUT')
                        <div class="chatbot-form-grid">
                            <label>Category
                                <select name="intent">
                                    @foreach($intents as $key => $label)
                                        <option value="{{ $key }}" @selected($item->intent === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label>Title
                                <input name="title" value="{{ $item->title }}" required>
                            </label>
                            <label class="chatbot-span-2">Content
                                <textarea name="content" rows="5" required>{{ $item->content }}</textarea>
                            </label>
                            <label class="chatbot-span-2">Keywords
                                <input name="keywords" value="{{ $item->keywords }}">
                            </label>
                        </div>
                        <div class="chatbot-checks">
                            <label><input type="checkbox" name="is_active" value="1" @checked($item->is_active)> Active</label>
                        </div>
                        <div class="chatbot-inline-actions">
                            <button class="admin-button admin-button--primary"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                            <form method="POST" action="{{ route('admin.chatbot.data.destroy', $item) }}" data-confirm-message="Delete this entry?">
                                @csrf @method('DELETE')
                                <button class="admin-button admin-button--danger"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </form>
                </details>
            @empty
                <div class="management-panel"><p style="padding:1rem;color:#64748b;">No entries yet. Import data or add manually above.</p></div>
            @endforelse
        </div>

        {{ $items->links() }}
    </div>
</x-admin-layout>
