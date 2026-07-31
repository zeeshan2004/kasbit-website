@php
    $alternativeValue = $item ? $item->alternatives->pluck('question')->implode("\n") : old('alternatives');
    $relatedValue = $item ? $item->relatedQuestions->pluck('question')->implode("\n") : old('related_questions');
@endphp
<div class="chatbot-form-grid">
    <label>Category
        <select name="category_id">
            <option value="">Uncategorized</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $item?->category_id) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </label>
    <label>Status
        <select name="status" required>
            @foreach(['approved', 'draft', 'disabled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $item?->status ?? 'approved') === $status)>{{ str($status)->title() }}</option>
            @endforeach
        </select>
    </label>
    <label class="chatbot-span-2">Question<textarea name="question" rows="2" required>{{ old('question', $item?->question) }}</textarea></label>
    <label class="chatbot-span-2">Answer<textarea name="answer" rows="6" required>{{ old('answer', $item?->answer) }}</textarea></label>
    <label>Alternative Questions <small>One per line</small><textarea name="alternatives" rows="5">{{ $alternativeValue }}</textarea></label>
    <label>Related Questions <small>One per line</small><textarea name="related_questions" rows="5">{{ $relatedValue }}</textarea></label>
    <label>Keywords <small>Comma-separated</small><input name="keywords" value="{{ old('keywords', $item?->keywords) }}"></label>
    <label>Priority<input type="number" name="priority" value="{{ old('priority', $item?->priority ?? 0) }}" min="0" max="1000" required></label>
</div>
