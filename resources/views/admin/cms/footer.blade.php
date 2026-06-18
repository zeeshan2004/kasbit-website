<x-admin-layout :title="'Footer CMS'" :header="'Footer CMS'">
    <div class="max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('footer-cms.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-kasbitBlue">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-shoe-prints text-kasbitBlue text-xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">Footer Identity &amp; Addresses</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Footer Logo</label>
                        @if($footer->logo)
                            <div class="relative inline-block mb-3">
                                <img src="{{ asset($footer->logo) }}" alt="Footer logo" class="h-28 w-48 object-contain bg-gray-50 rounded-lg p-2">
                                <button type="button" onclick="document.getElementById('delete_logo').value='1'; this.closest('.relative').style.display='none';" class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-red-500 text-white" title="Remove logo">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <input type="hidden" name="delete_logo" id="delete_logo" value="0">
                    </div>

                    @foreach([1, 2, 3] as $index)
                        <div class="{{ $index === 3 ? 'md:col-span-2' : '' }}">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Location {{ $index }} Address</label>
                            <textarea name="address_{{ $index }}" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Full campus address">{{ old("address_{$index}", $footer->{"address_{$index}"}) }}</textarea>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-share-nodes text-indigo-500 text-xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">Social Links</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <input type="text" name="facebook_url" value="{{ old('facebook_url', $footer->facebook_url) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Facebook URL">
                    <input type="text" name="instagram_url" value="{{ old('instagram_url', $footer->instagram_url) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Instagram URL">
                    <input type="text" name="linkedin_url" value="{{ old('linkedin_url', $footer->linkedin_url) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="LinkedIn URL">
                </div>
            </section>

            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-amber-500">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-images text-amber-500 text-xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">Footer Gallery</h2>
                </div>

                @php($gallery = array_values(array_filter($footer->gallery_images ?? [])))
                @if(count($gallery))
                    <h3 class="text-sm font-bold text-gray-700 mb-3">Existing Images</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                        @foreach($gallery as $index => $image)
                            <div class="border border-gray-200 rounded-lg p-2 bg-gray-50">
                                <img src="{{ asset($image) }}" alt="Gallery image {{ $index + 1 }}" class="w-full h-24 object-cover rounded">
                                <label class="flex items-center gap-2 mt-2 text-xs font-semibold text-red-600">
                                    <input type="checkbox" name="delete_existing_gallery[{{ $index }}]" value="1">
                                    Remove
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                    <h3 class="text-sm font-bold text-gray-700">Add Images</h3>
                    <span class="text-xs text-gray-500">No fixed image limit</span>
                </div>
                <div id="footer-gallery-fields" class="space-y-3">
                    <div class="footer-gallery-field flex flex-col sm:flex-row sm:items-center gap-3 border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <input type="file" name="gallery_images[]" accept="image/*" multiple class="min-w-0 w-full flex-1 text-sm">
                        <button type="button" class="remove-footer-gallery hidden self-end sm:self-auto w-9 h-9 text-red-600 hover:bg-red-50 rounded-lg" title="Remove upload">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button type="button" id="add-footer-gallery" class="mt-4 px-4 py-2 border border-kasbitBlue text-kasbitBlue rounded-lg hover:bg-blue-50 font-semibold">
                    <i class="fa-solid fa-plus mr-2"></i>Add Another Image
                </button>
            </section>

            <section class="bg-white rounded-lg shadow-md p-6 border-l-4 border-emerald-500">
                <div class="flex items-center mb-5">
                    <i class="fa-solid fa-map-location-dot text-emerald-500 text-xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">Map &amp; Bottom Bar</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Map Heading</label>
                        <input type="text" name="map_title" value="{{ old('map_title', $footer->map_title ?: 'Location Map') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Google Maps Embed URL</label>
                        <input type="text" name="map_embed_url" value="{{ old('map_embed_url', $footer->map_embed_url) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Paste Google Maps embed URL or iframe code">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Copyright Text</label>
                        <input type="text" name="copyright_text" value="{{ old('copyright_text', $footer->copyright_text) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Footer Color</label>
                        <input type="color" name="background_color" value="{{ old('background_color', $footer->background_color ?: '#2756a5') }}" class="w-full h-11 border border-gray-300 rounded-lg p-1">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bottom Bar Color</label>
                        <input type="color" name="bottom_bar_color" value="{{ old('bottom_bar_color', $footer->bottom_bar_color ?: '#064f80') }}" class="w-full h-11 border border-gray-300 rounded-lg p-1">
                    </div>
                </div>
            </section>

            <div class="flex items-center justify-between bg-white rounded-lg shadow-md p-5">
                <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $footer->exists ? $footer->is_active : true))>
                    Show footer on frontend
                </label>
                <button type="submit" class="px-6 py-2 bg-kasbitBlue text-white rounded-lg hover:bg-blue-800">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Save Footer
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

<script data-admin-page-script>
document.getElementById('add-footer-gallery')?.addEventListener('click', function () {
    const wrapper = document.getElementById('footer-gallery-fields');
    const field = wrapper.querySelector('.footer-gallery-field').cloneNode(true);
    field.querySelector('input').value = '';
    field.querySelector('.remove-footer-gallery').classList.remove('hidden');
    wrapper.appendChild(field);
});

document.getElementById('footer-gallery-fields')?.addEventListener('click', function (event) {
    const button = event.target.closest('.remove-footer-gallery');
    if (button) {
        button.closest('.footer-gallery-field').remove();
    }
});
</script>
