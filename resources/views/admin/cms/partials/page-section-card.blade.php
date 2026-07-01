<div class="border border-gray-200 rounded-lg p-4" id="page-section-card-{{ $slide->id }}" data-page-section-card>
    <form method="POST"
          action="{{ route('header-menu-page-slides.update', $slide) }}"
          data-page-section-form
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @if($slide->image)
                <img src="{{ asset($slide->image) }}?v={{ $slide->updated_at?->timestamp }}"
                     alt="{{ $slide->title }}"
                     class="h-44 w-full object-cover rounded-lg shadow">
            @else
                <div class="h-44 w-full rounded-lg bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                    <i class="fa-solid fa-align-left text-3xl"></i>
                </div>
            @endif
            <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                <input type="text" name="title" value="{{ $slide->title }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <div>
                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP up to 10MB.</p>
                </div>
                <textarea name="description" rows="4" class="md:col-span-2 w-full px-3 py-2 border border-gray-300 rounded-lg">{{ $slide->description }}</textarea>
                <input type="number" min="0" name="sort_order" value="{{ $slide->sort_order }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <select name="image_position" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="left" @selected($slide->image_position === 'left')>Image Left / Text Right</option>
                    <option value="right" @selected($slide->image_position === 'right')>Image Right / Text Left</option>
                </select>
                <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <input type="checkbox" name="is_active" value="1" @checked($slide->is_active)>
                    Show on frontend
                </label>
            </div>
        </div>
        <div class="flex justify-end mt-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg">
                Update Block
            </button>
        </div>
    </form>
    <div class="flex items-center justify-end mt-3">
        <form method="POST"
              action="{{ route('header-menu-page-slides.destroy', $slide) }}"
              data-page-section-form
              onsubmit="return confirm('Delete this content block?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Delete</button>
        </form>
    </div>
</div>
