<x-admin-layout :title="$headerMenu->name . ' CMS'" :header="$headerMenu->name . ' CMS'">
    <div class="max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-kasbitBlue">{{ $headerMenu->parent?->name }}</p>
                <h1 class="text-3xl font-bold text-gray-900">{{ $headerMenu->name }}</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('header-menu.edit', $headerMenu) }}" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">
                    <i class="fa-solid fa-link mr-2"></i>Edit Menu Link
                </a>
                <a href="{{ $headerMenu->link ?: '#' }}" target="_blank" class="px-4 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-800">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-2"></i>View Page
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('header-menu.page.update', $headerMenu) }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-kasbitBlue">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-sliders text-kasbitBlue text-xl mr-3"></i>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Page Settings</h2>
                        <p class="text-sm text-gray-500">Manage the page heading and identity here.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Small Heading</label>
                        <input type="text" name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="{{ $headerMenu->parent?->name }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Accent Color</label>
                        <input type="color" name="accent_color" value="{{ old('accent_color', $page->accent_color ?: '#07559d') }}" class="w-full h-11 border border-gray-300 rounded-lg p-1">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Page Title</label>
                        <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Short Introduction</label>
                        <textarea name="subtitle" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('subtitle', $page->subtitle) }}</textarea>
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-kasbitBlue text-white rounded-lg hover:bg-blue-800 font-semibold">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Save {{ $headerMenu->name }}
                </button>
            </div>
        </form>

        <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-violet-500">
            <div class="flex items-center justify-between gap-4 mb-5">
                <div class="flex items-center">
                    <i class="fa-solid fa-layer-group text-violet-500 text-xl mr-3"></i>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Page Sections</h2>
                        <p class="text-sm text-gray-500">Add multiple image and text sections. Every saved section remains available for editing or deletion.</p>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-4">Add Content Blocks</h3>
            <form method="POST"
                  action="{{ route('header-menu-page-slides.store', $page) }}"
                  enctype="multipart/form-data"
                  data-page-section-form
                  class="mb-8">
                @csrf
                <div id="page-slide-fields" class="space-y-4">
                    <div class="page-slide-field border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-800">Content Block 1</h4>
                            <button type="button" class="remove-page-slide hidden text-red-600 hover:text-red-800" aria-label="Remove block">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                                <input type="text" name="slides[0][title]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Image</label>
                                <input type="file" name="slides[0][image]" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <p class="mt-1 text-xs text-gray-500">The image is optional when text is provided.</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Text</label>
                                <textarea name="slides[0][description]" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                                <input type="number" min="0" name="slides[0][sort_order]" value="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Section Layout</label>
                                <select name="slides[0][image_position]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="left">Image Left / Text Right</option>
                                    <option value="right">Image Right / Text Left</option>
                                </select>
                            </div>
                            <label class="md:col-span-2 inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="slides[0][is_active]" value="1" checked>
                                Show on frontend
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                    <button type="button" id="add-page-slide" class="px-4 py-2 border border-kasbitBlue text-kasbitBlue rounded-lg hover:bg-blue-50 font-medium">
                        <i class="fa-solid fa-plus mr-2"></i>Add Another Block
                    </button>
                    <button type="submit" class="px-6 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-800 font-medium">
                        Save Content Blocks
                    </button>
                </div>
            </form>

            <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Content Blocks</h3>
            <div class="space-y-4">
                @forelse($page->slides as $slide)
                    <div class="border border-gray-200 rounded-lg p-4">
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
                @empty
                    <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                        No content blocks added yet.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-admin-layout>

<script data-admin-page-script>
(() => {
    let pageSlideIndex = 1;
    const wrapper = document.getElementById('page-slide-fields');

    document.getElementById('add-page-slide')?.addEventListener('click', () => {
        const field = wrapper?.querySelector('.page-slide-field')?.cloneNode(true);
        if (!field) return;

        field.querySelector('h4').textContent = 'Content Block ' + (pageSlideIndex + 1);
        field.querySelector('.remove-page-slide').classList.remove('hidden');

        field.querySelectorAll('input, textarea, select').forEach((input) => {
            input.name = input.name.replace(/slides\[\d+\]/, 'slides[' + pageSlideIndex + ']');

            if (input.type === 'checkbox') {
                input.checked = true;
            } else if (input.type === 'number') {
                input.value = pageSlideIndex;
            } else if (input.tagName === 'SELECT') {
                input.value = 'left';
            } else {
                input.value = '';
            }
        });

        wrapper.appendChild(field);
        pageSlideIndex++;
    });

    wrapper?.addEventListener('click', (event) => {
        const button = event.target.closest('.remove-page-slide');
        if (button) button.closest('.page-slide-field').remove();
    });
})();
</script>
