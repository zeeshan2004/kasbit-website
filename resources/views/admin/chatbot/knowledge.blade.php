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
</x-admin-layout>
