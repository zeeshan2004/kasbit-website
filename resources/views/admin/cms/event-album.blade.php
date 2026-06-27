<x-admin-layout :title="$album->title . ' Photos'" :header="'Event Album: ' . $album->title">
    <div class="max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-sm font-semibold text-fuchsia-700">Event Gallery</p>
                <h1 class="text-3xl font-bold text-gray-900">{{ $album->title }}</h1>
            </div>
            <a href="{{ route('header-menu.page.edit', $album->page->menu) }}"
               class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Albums
            </a>
        </div>

        <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-fuchsia-500">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Add Photos</h2>
            <form method="POST"
                  action="{{ route('event-albums.photos.store', $album) }}"
                  enctype="multipart/form-data"
                  class="border border-gray-200 rounded-lg p-5 bg-gray-50">
                @csrf
                <label class="block text-sm font-semibold text-gray-700 mb-2">Choose Images (select multiple)</label>
                <input type="file" name="images[]" accept="image/*" multiple required class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG or WebP. Each image up to 10MB.</p>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-6 py-2 bg-fuchsia-600 hover:bg-fuchsia-700 text-white rounded-lg font-semibold">
                        <i class="fa-solid fa-upload mr-2"></i>Upload Photos
                    </button>
                </div>
            </form>
        </section>

        <section class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Photos ({{ $album->images->count() }})</h2>
            @if($album->images->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($album->images as $image)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ asset($image->image) }}?v={{ $image->updated_at?->timestamp }}"
                                 alt="{{ $image->caption ?: 'Photo' }}" class="h-44 w-full object-cover">
                            <form method="POST"
                                  action="{{ route('event-album-images.update', $image) }}"
                                  enctype="multipart/form-data"
                                  class="p-3 space-y-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="caption" value="{{ $image->caption }}" placeholder="Caption (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                <div class="flex items-center gap-2">
                                    <input type="number" min="0" name="sort_order" value="{{ $image->sort_order }}" class="w-20 px-2 py-2 border border-gray-300 rounded-lg text-sm" aria-label="Display order">
                                    <label class="inline-flex items-center gap-1 text-xs font-semibold text-gray-700">
                                        <input type="checkbox" name="is_active" value="1" @checked($image->is_active)>
                                        Show
                                    </label>
                                    <input type="file" name="image" accept="image/*" class="flex-1 min-w-0 text-xs" aria-label="Replace photo">
                                </div>
                                <button type="submit" class="px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg text-sm font-semibold">Update</button>
                            </form>
                            <form method="POST"
                                  action="{{ route('event-album-images.destroy', $image) }}"
                                  onsubmit="return confirm('Delete this photo?')"
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
                    No photos in this album yet.
                </div>
            @endif
        </section>
    </div>
</x-admin-layout>
