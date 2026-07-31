<div class="chatbot-form-grid">
    <label>Category
        <select name="category_id">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $suggestion?->category_id) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </label>
    <label>Display Order<input type="number" name="display_order" value="{{ old('display_order', $suggestion?->display_order ?? 0) }}" min="0" max="1000"></label>
    <label class="chatbot-span-2">Question<input name="question" value="{{ old('question', $suggestion?->question) }}" maxlength="500" required></label>
    <label class="chatbot-span-2">Fixed Answer <small>Optional; otherwise normal matching is used.</small><textarea name="answer" rows="4">{{ old('answer', $suggestion?->answer) }}</textarea></label>
</div>
<div class="chatbot-checks">
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $suggestion?->is_active ?? true))> Active</label>
    <label><input type="checkbox" name="show_on_welcome" value="1" @checked(old('show_on_welcome', $suggestion?->show_on_welcome ?? true))> Show on welcome</label>
</div>
