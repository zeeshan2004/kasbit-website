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
                    @if(in_array(strtolower($headerMenu->name), ['fee structure', 'program profile', 'admission policy'], true))
                        <div class="md:col-span-2 rounded-xl border border-red-100 bg-red-50 p-5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-800 mb-2">PDF File</label>
                                    <input type="file"
                                           name="pdf_file"
                                           accept="application/pdf,.pdf"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white">
                                    <p class="mt-2 text-xs text-gray-500">PDF only, maximum file size 20MB.</p>
                                </div>
                                @if($page->pdf_file)
                                    <div class="min-w-0 rounded-lg border border-red-200 bg-white px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-800 break-all">
                                            <i class="fa-solid fa-file-pdf text-red-500 mr-2"></i>
                                            {{ $page->pdf_original_name ?: basename($page->pdf_file) }}
                                        </p>
                                        <label class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-red-600">
                                            <input type="checkbox" name="remove_pdf" value="1">
                                            Remove current PDF
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
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

        <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-sky-500">
            <div class="flex items-center mb-5">
                <i class="fa-solid fa-images text-sky-500 text-xl mr-3"></i>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Photo Gallery</h2>
                    <p class="text-sm text-gray-500">Upload one or more images. They appear as a grid on the page. Add captions or remove images below.</p>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-4">Add Images</h3>
            <form method="POST"
                  action="{{ route('page-gallery.store', $page) }}"
                  enctype="multipart/form-data"
                  class="mb-8 border border-gray-200 rounded-lg p-5 bg-gray-50">
                @csrf
                <label class="block text-sm font-semibold text-gray-700 mb-2">Choose Images (you can select multiple)</label>
                <input type="file" name="images[]" accept="image/*" multiple required class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP. Each image up to 10MB.</p>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg font-semibold">
                        <i class="fa-solid fa-upload mr-2"></i>Upload Images
                    </button>
                </div>
            </form>

            <h3 class="text-lg font-bold text-gray-800 mb-4">Manage Images</h3>
            @if($page->galleryImages->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($page->galleryImages as $galleryImage)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ asset($galleryImage->image) }}?v={{ $galleryImage->updated_at?->timestamp }}"
                                 alt="{{ $galleryImage->caption ?: 'Gallery image' }}"
                                 class="h-44 w-full object-cover">
                            <form method="POST"
                                  action="{{ route('page-gallery.update', $galleryImage) }}"
                                  enctype="multipart/form-data"
                                  class="p-3 space-y-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="caption" value="{{ $galleryImage->caption }}" placeholder="Caption (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <div class="flex items-center gap-2">
                                    <input type="number" min="0" name="sort_order" value="{{ $galleryImage->sort_order }}" class="w-20 px-2 py-2 border border-gray-300 rounded-lg text-sm" aria-label="Display order">
                                    <label class="inline-flex items-center gap-1 text-xs font-semibold text-gray-700">
                                        <input type="checkbox" name="is_active" value="1" @checked($galleryImage->is_active)>
                                        Show
                                    </label>
                                    <input type="file" name="image" accept="image/*" class="flex-1 min-w-0 text-xs" aria-label="Replace image">
                                </div>
                                <div class="flex items-center justify-between gap-2 pt-1">
                                    <button type="submit" class="px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg text-sm font-semibold">Update</button>
                                </div>
                            </form>
                            <form method="POST"
                                  action="{{ route('page-gallery.destroy', $galleryImage) }}"
                                  onsubmit="return confirm('Delete this image?')"
                                  class="px-3 pb-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm">Delete</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                    No gallery images yet.
                </div>
            @endif
        </section>

        @php($supportsCalendarTables = in_array(strtolower($headerMenu->name), ['academic calendar', 'academic scholarship'], true))
        @if($supportsCalendarTables)
            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-700">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-table-cells text-red-700 text-xl mr-3"></i>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $headerMenu->name }} Tables</h2>
                        <p class="text-sm text-gray-500">Create simple schedules, 3-column tables (with your own column headings), and note bars for this page.</p>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Add Calendar Table</h3>
                <form method="POST"
                      action="{{ route('academic-calendar-tables.store', $page) }}"
                      data-academic-calendar-form
                      class="mb-8 border border-gray-200 rounded-lg p-5 bg-gray-50">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Table Title</label>
                            <input type="text" name="title" placeholder="FALL SEMESTER 2025" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Table Type</label>
                            <select name="type" data-calendar-type class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="semester">Simple Schedule</option>
                                <option value="holidays">3 Column Table</option>
                                <option value="note">Note Bar</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Simple: activity/date. 3 Column: occasion/days/date. Note: write note text only.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="sort_order" min="1" value="{{ ((int) $page->academicCalendarTables->max('sort_order')) + 1 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div data-calendar-headers class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5 hidden">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Column 1 Heading</label>
                            <input type="text" name="col1_label" placeholder="Excellence" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Column 2 Heading</label>
                            <input type="text" name="col2_label" placeholder="CGPA" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Column 3 Heading</label>
                            <input type="text" name="col3_label" placeholder="Scholarship" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div data-calendar-rows class="space-y-3">
                        <div data-calendar-row class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 bg-white border border-gray-200 rounded-lg">
                            <div class="md:col-span-4" data-calendar-field="occasion">
                                <label class="block text-xs font-bold text-gray-600 mb-1" data-calendar-occasion-label>Activity / Occasion</label>
                                <input type="text" name="rows[0][occasion]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-3" data-calendar-field="days">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Days</label>
                                <input type="text" name="rows[0][days]" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-4" data-calendar-field="date">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Date</label>
                                <input type="text" name="rows[0][date_text]" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-1 flex items-end justify-end pb-2">
                                <button type="button" data-remove-calendar-row class="hidden text-red-500 hover:text-red-700" aria-label="Remove row">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            <input type="hidden" name="rows[0][sort_order]" value="0">
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                        <button type="button" data-add-calendar-row class="px-4 py-2 border border-red-700 text-red-700 rounded-lg hover:bg-red-50 font-semibold">
                            <i class="fa-solid fa-plus mr-2"></i>Add Calendar Row
                        </button>
                        <div class="flex items-center gap-4">
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="is_active" value="1" checked>
                                Show on frontend
                            </label>
                            <button type="submit" class="px-6 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg font-semibold">
                                Save Calendar Table
                            </button>
                        </div>
                    </div>
                </form>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Calendar Tables</h3>
                <div class="space-y-6">
                    @forelse($page->academicCalendarTables as $calendarTable)
                        <div class="border border-gray-200 rounded-lg p-5">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <h4 class="font-bold text-gray-800">{{ $calendarTable->title }}</h4>
                                <span class="text-xs font-semibold text-gray-500">Table {{ $loop->iteration }}</span>
                            </div>
                            <form method="POST"
                                  action="{{ route('academic-calendar-tables.update', $calendarTable) }}"
                                  data-academic-calendar-form>
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-5">
                                    <input type="text" name="title" value="{{ $calendarTable->title }}" placeholder="Optional table title" class="md:col-span-2 w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <select name="type" data-calendar-type class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                        <option value="semester" @selected($calendarTable->type === 'semester')>Simple Schedule</option>
                                        <option value="holidays" @selected($calendarTable->type === 'holidays')>3 Column Table</option>
                                        <option value="note" @selected($calendarTable->type === 'note')>Note Bar</option>
                                    </select>
                                    <input type="number" name="sort_order" min="1" value="{{ $calendarTable->sort_order }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" aria-label="Display Order">
                                </div>

                                <div data-calendar-headers class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5 {{ $calendarTable->type === 'holidays' ? '' : 'hidden' }}">
                                    <input type="text" name="col1_label" value="{{ $calendarTable->col1_label }}" placeholder="Column 1 Heading" class="w-full px-4 py-2 border border-gray-300 rounded-lg" aria-label="Column 1 heading">
                                    <input type="text" name="col2_label" value="{{ $calendarTable->col2_label }}" placeholder="Column 2 Heading" class="w-full px-4 py-2 border border-gray-300 rounded-lg" aria-label="Column 2 heading">
                                    <input type="text" name="col3_label" value="{{ $calendarTable->col3_label }}" placeholder="Column 3 Heading" class="w-full px-4 py-2 border border-gray-300 rounded-lg" aria-label="Column 3 heading">
                                </div>

                                <div data-calendar-rows class="space-y-3">
                                    @foreach($calendarTable->rows as $row)
                                        <div data-calendar-row class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="md:col-span-4" data-calendar-field="occasion">
                                                <label class="block text-xs font-bold text-gray-600 mb-1" data-calendar-occasion-label>Activity / Occasion</label>
                                                <input type="text" name="rows[{{ $loop->index }}][occasion]" value="{{ $row->occasion }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Occasion or activity">
                                            </div>
                                            <div class="md:col-span-3" data-calendar-field="days">
                                                <label class="block text-xs font-bold text-gray-600 mb-1">Days</label>
                                                <input type="text" name="rows[{{ $loop->index }}][days]" value="{{ $row->days }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Days">
                                            </div>
                                            <div class="md:col-span-4" data-calendar-field="date">
                                                <label class="block text-xs font-bold text-gray-600 mb-1">Date</label>
                                                <input type="text" name="rows[{{ $loop->index }}][date_text]" value="{{ $row->date_text }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Date">
                                            </div>
                                            <div class="md:col-span-1 flex items-center justify-end">
                                                <button type="button" data-remove-calendar-row class="text-red-500 hover:text-red-700" aria-label="Remove row">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="rows[{{ $loop->index }}][sort_order]" value="{{ $row->sort_order }}">
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                                    <button type="button" data-add-calendar-row class="px-4 py-2 border border-red-700 text-red-700 rounded-lg hover:bg-red-50 font-semibold">
                                        <i class="fa-solid fa-plus mr-2"></i>Add Calendar Row
                                    </button>
                                    <div class="flex items-center gap-4">
                                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <input type="checkbox" name="is_active" value="1" @checked($calendarTable->is_active)>
                                            Show on frontend
                                        </label>
                                        <button type="submit" class="px-5 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg font-semibold">
                                            Update Table
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form method="POST"
                                  action="{{ route('academic-calendar-tables.destroy', $calendarTable) }}"
                                  onsubmit="return confirm('Delete this calendar table?')"
                                  class="flex justify-end mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Delete Table</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                            No academic calendar tables added yet.
                        </div>
                    @endforelse
                </div>
            </section>
        @endif

        @if(strcasecmp($headerMenu->name, 'Academic Departments') === 0)
            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-emerald-600">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-building-columns text-emerald-600 text-xl mr-3"></i>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Academic Departments</h2>
                        <p class="text-sm text-gray-500">Add each department with an optional image, head of department and link. Saved departments appear in the list below for editing or deletion.</p>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Add Department</h3>
                <form method="POST"
                      action="{{ route('departments.store', $page) }}"
                      enctype="multipart/form-data"
                      class="mb-8 border border-gray-200 rounded-lg p-5 bg-gray-50">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Department Name</label>
                            <input type="text" name="name" placeholder="Department of Computer Science" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Head of Department</label>
                            <input type="text" name="head_of_department" placeholder="Dr. John Doe" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Image</label>
                            <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white">
                            <p class="mt-1 text-xs text-gray-500">Optional. JPG, PNG or WebP up to 10MB.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Link</label>
                            <input type="text" name="link" placeholder="/pages/some-page or https://example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="sort_order" min="0" value="{{ ((int) $page->departments->max('sort_order')) + 1 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 mt-8">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Show on frontend
                        </label>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold">
                            Save Department
                        </button>
                    </div>
                </form>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Departments</h3>
                <div class="space-y-4">
                    @forelse($page->departments as $department)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <form method="POST"
                                  action="{{ route('departments.update', $department) }}"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    @if($department->image)
                                        <img src="{{ asset($department->image) }}?v={{ $department->updated_at?->timestamp }}"
                                             alt="{{ $department->name }}"
                                             class="h-44 w-full object-cover rounded-lg shadow">
                                    @else
                                        <div class="h-44 w-full rounded-lg bg-gray-100 border border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-building-columns text-3xl"></i>
                                        </div>
                                    @endif
                                    <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <input type="text" name="name" value="{{ $department->name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Department name">
                                        <input type="text" name="head_of_department" value="{{ $department->head_of_department }}" placeholder="Head of Department" class="w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Head of department">
                                        <div>
                                            <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                            <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image.</p>
                                        </div>
                                        <input type="text" name="link" value="{{ $department->link }}" placeholder="Link" class="w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Department link">
                                        <textarea name="description" rows="3" class="md:col-span-2 w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Description">{{ $department->description }}</textarea>
                                        <input type="number" min="0" name="sort_order" value="{{ $department->sort_order }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Display order">
                                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <input type="checkbox" name="is_active" value="1" @checked($department->is_active)>
                                            Show on frontend
                                        </label>
                                    </div>
                                </div>
                                <div class="flex justify-end mt-4 pt-4 border-t border-gray-100">
                                    <button type="submit" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg">
                                        Update Department
                                    </button>
                                </div>
                            </form>
                            <div class="flex items-center justify-end mt-3">
                                <form method="POST"
                                      action="{{ route('departments.destroy', $department) }}"
                                      onsubmit="return confirm('Delete this department?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                            No departments added yet.
                        </div>
                    @endforelse
                </div>
            </section>
        @endif

        @if($headerMenu->isDescendantOf('Programs') && strcasecmp($headerMenu->name, 'Programs') !== 0)
            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-table-list text-orange-500 text-xl mr-3"></i>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Program Schema Tables</h2>
                        <p class="text-sm text-gray-500">Create multiple semester and subject tables for this program.</p>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Add Schema Table</h3>
                <form method="POST"
                      action="{{ route('program-schemas.store', $page) }}"
                      data-program-schema-form
                      class="mb-8 border border-gray-200 rounded-lg p-5 bg-gray-50">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Table Title</label>
                            <input type="text" name="title" placeholder="Semester I" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="sort_order" min="1" value="{{ ((int) $page->programSchemaTables->max('sort_order')) + 1 }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>

                    <div data-schema-rows class="space-y-3">
                        <div data-schema-row class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 bg-white border border-gray-200 rounded-lg">
                            <div class="md:col-span-7">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Subject / Label</label>
                                <input type="text" name="rows[0][subject]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Credit Hours</label>
                                <input type="text" name="rows[0][credit_hours]" placeholder="3 + 0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="md:col-span-2 flex items-end gap-3 pb-2">
                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700">
                                    <input type="checkbox" name="rows[0][is_total]" value="1"> Bold row
                                </label>
                                <button type="button" data-remove-schema-row class="hidden text-red-500 hover:text-red-700" aria-label="Remove row">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            <input type="hidden" name="rows[0][sort_order]" value="0">
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                        <button type="button" data-add-schema-row class="px-4 py-2 border border-orange-500 text-orange-600 rounded-lg hover:bg-orange-50 font-semibold">
                            <i class="fa-solid fa-plus mr-2"></i>Add Subject Row
                        </button>
                        <div class="flex items-center gap-4">
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <input type="checkbox" name="is_active" value="1" checked>
                                Show on frontend
                            </label>
                            <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-semibold">
                                Save Schema Table
                            </button>
                        </div>
                    </div>
                </form>

                <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Schema Tables</h3>
                <div class="space-y-6">
                    @forelse($page->programSchemaTables as $schemaTable)
                        <div class="border border-gray-200 rounded-lg p-5">
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <h4 class="font-bold text-gray-800">{{ $schemaTable->title }}</h4>
                                <span class="text-xs font-semibold text-gray-500">Table {{ $loop->iteration }}</span>
                            </div>
                            <form method="POST"
                                  action="{{ route('program-schemas.update', $schemaTable) }}"
                                  data-program-schema-form>
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                                    <input type="text" name="title" value="{{ $schemaTable->title }}" required class="md:col-span-2 w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <input type="number" name="sort_order" min="1" value="{{ $schemaTable->sort_order }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" aria-label="Display Order">
                                </div>

                                <div data-schema-rows class="space-y-3">
                                    @foreach($schemaTable->rows as $row)
                                        <div data-schema-row class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                            <input type="text" name="rows[{{ $loop->index }}][subject]" value="{{ $row->subject }}" required class="md:col-span-7 w-full px-3 py-2 border border-gray-300 rounded-lg" aria-label="Subject or label">
                                            <input type="text" name="rows[{{ $loop->index }}][credit_hours]" value="{{ $row->credit_hours }}" placeholder="3 + 0" class="md:col-span-2 w-full px-3 py-2 border border-gray-300 rounded-lg">
                                            <div class="md:col-span-2 flex items-center gap-3">
                                                <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700">
                                                    <input type="checkbox" name="rows[{{ $loop->index }}][is_total]" value="1" @checked($row->is_total)> Bold row
                                                </label>
                                                <button type="button" data-remove-schema-row class="text-red-500 hover:text-red-700" aria-label="Remove row">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="rows[{{ $loop->index }}][sort_order]" value="{{ $row->sort_order }}">
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                                    <button type="button" data-add-schema-row class="px-4 py-2 border border-orange-500 text-orange-600 rounded-lg hover:bg-orange-50 font-semibold">
                                        <i class="fa-solid fa-plus mr-2"></i>Add Subject Row
                                    </button>
                                    <div class="flex items-center gap-4">
                                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <input type="checkbox" name="is_active" value="1" @checked($schemaTable->is_active)>
                                            Show on frontend
                                        </label>
                                        <button type="submit" class="px-5 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg font-semibold">
                                            Update Table
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form method="POST"
                                  action="{{ route('program-schemas.destroy', $schemaTable) }}"
                                  onsubmit="return confirm('Delete this schema table?')"
                                  class="flex justify-end mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">Delete Table</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 border border-dashed border-gray-300 rounded-lg">
                            No program schema tables added yet.
                        </div>
                    @endforelse
                </div>
            </section>
        @endif
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

    document.querySelectorAll('[data-academic-calendar-form]').forEach((form) => {
        const rows = form.querySelector('[data-calendar-rows]');
        const addButton = form.querySelector('[data-add-calendar-row]');
        const typeSelect = form.querySelector('[data-calendar-type]');

        const syncCalendarFields = () => {
            const type = typeSelect?.value || 'semester';
            const headers = form.querySelector('[data-calendar-headers]');
            headers?.classList.toggle('hidden', type !== 'holidays');
            const setSpan = (element, span) => {
                element?.classList.remove('md:col-span-3', 'md:col-span-4', 'md:col-span-5', 'md:col-span-6', 'md:col-span-11');
                if (span) element?.classList.add(span);
            };

            form.querySelectorAll('[data-calendar-row]').forEach((row) => {
                const occasion = row.querySelector('[data-calendar-field="occasion"]');
                const days = row.querySelector('[data-calendar-field="days"]');
                const date = row.querySelector('[data-calendar-field="date"]');
                const label = row.querySelector('[data-calendar-occasion-label]');

                if (type === 'note') {
                    setSpan(occasion, 'md:col-span-11');
                    days?.classList.add('hidden');
                    date?.classList.add('hidden');
                    if (label) label.textContent = 'Note Text';
                } else if (type === 'semester') {
                    setSpan(occasion, 'md:col-span-5');
                    days?.classList.add('hidden');
                    date?.classList.remove('hidden');
                    setSpan(date, 'md:col-span-6');
                    if (label) label.textContent = 'Activity';
                } else {
                    setSpan(occasion, 'md:col-span-4');
                    setSpan(days, 'md:col-span-3');
                    setSpan(date, 'md:col-span-4');
                    days?.classList.remove('hidden');
                    date?.classList.remove('hidden');
                    if (label) label.textContent = 'Occasion';
                }
            });
        };

        typeSelect?.addEventListener('change', syncCalendarFields);

        addButton?.addEventListener('click', () => {
            const source = rows?.querySelector('[data-calendar-row]');
            if (!source) return;

            const row = source.cloneNode(true);
            const index = rows.querySelectorAll('[data-calendar-row]').length;

            row.querySelectorAll('input').forEach((input) => {
                input.name = input.name.replace(/rows\[\d+\]/, 'rows[' + index + ']');

                if (input.type === 'hidden') {
                    input.value = index;
                } else {
                    input.value = '';
                }
            });

            row.querySelector('[data-remove-calendar-row]')?.classList.remove('hidden');
            rows.appendChild(row);
            syncCalendarFields();
        });

        rows?.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-remove-calendar-row]');
            if (!removeButton || rows.querySelectorAll('[data-calendar-row]').length === 1) return;
            removeButton.closest('[data-calendar-row]').remove();
        });

        form.addEventListener('submit', () => {
            rows?.querySelectorAll('[data-calendar-row]').forEach((row, index) => {
                row.querySelectorAll('input[name^="rows["]').forEach((input) => {
                    input.name = input.name.replace(/rows\[\d+\]/, 'rows[' + index + ']');
                    if (input.type === 'hidden') input.value = index;
                });
            });
        });

        syncCalendarFields();
    });

    document.querySelectorAll('[data-program-schema-form]').forEach((form) => {
        const rows = form.querySelector('[data-schema-rows]');
        const addButton = form.querySelector('[data-add-schema-row]');

        addButton?.addEventListener('click', () => {
            const source = rows?.querySelector('[data-schema-row]');
            if (!source) return;

            const row = source.cloneNode(true);
            const index = rows.querySelectorAll('[data-schema-row]').length;

            row.querySelectorAll('input').forEach((input) => {
                input.name = input.name.replace(/rows\[\d+\]/, 'rows[' + index + ']');

                if (input.type === 'checkbox') {
                    input.checked = false;
                } else if (input.type === 'hidden') {
                    input.value = index;
                } else {
                    input.value = '';
                }
            });

            row.querySelector('[data-remove-schema-row]')?.classList.remove('hidden');
            rows.appendChild(row);
        });

        rows?.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-remove-schema-row]');
            if (!removeButton || rows.querySelectorAll('[data-schema-row]').length === 1) return;
            removeButton.closest('[data-schema-row]').remove();
        });

        form.addEventListener('submit', () => {
            rows?.querySelectorAll('[data-schema-row]').forEach((row, index) => {
                row.querySelectorAll('input[name^="rows["]').forEach((input) => {
                    input.name = input.name.replace(/rows\[\d+\]/, 'rows[' + index + ']');
                    if (input.type === 'hidden') input.value = index;
                });
            });
        });
    });
})();
</script>
