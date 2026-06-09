<x-admin-layout :title="'Header Menu CMS'" :header="'Header Menu CMS'">
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

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-kasbitBlue">
            <div class="flex items-center mb-5">
                <i class="fa-solid fa-heading text-kasbitBlue text-xl mr-3"></i>
                <h2 class="text-2xl font-bold text-gray-800">Header Settings</h2>
            </div>

            <form method="POST" action="{{ route('header-menu.settings.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Header Logo</label>
                    @if($home->header_logo_url ?? false)
                        <div class="relative mb-3 inline-block">
                            <img src="{{ asset($home->header_logo_url) }}" alt="Logo" class="h-16 object-contain">
                            <button type="button" onclick="document.getElementById('delete_header_logo').value='1'; this.closest('div').style.display='none';" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif
                    <input type="file" name="header_logo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                    <input type="hidden" name="delete_header_logo" id="delete_header_logo" value="0">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Phone</label>
                    <input type="text" name="header_phone" value="{{ old('header_phone', $home->header_phone ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="+92 XXX XXXXXXX">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Email</label>
                    <input type="email" name="header_email" value="{{ old('header_email', $home->header_email ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="info@kasbit.edu.pk">
                </div>

                <div class="md:col-span-2 border-t border-gray-200 pt-5 mt-2">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Top Header Bar</h3>
                            <p class="text-sm text-gray-500">Campus links and social icons shown above the navbar.</p>
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox" name="top_header_is_active" value="1" @checked(old('top_header_is_active', $home->exists ? $home->top_header_is_active : true))>
                            Show top bar
                        </label>
                    </div>
                </div>

                @foreach([1, 2, 3] as $index)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Campus {{ $index }} Name</label>
                        <input type="text" name="top_location_{{ $index }}_name" value="{{ old("top_location_{$index}_name", $home->{"top_location_{$index}_name"} ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="{{ ['SMCHS', 'HYDERI', 'GULSHAN'][$index - 1] }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Campus {{ $index }} Link</label>
                        <input type="text" name="top_location_{{ $index }}_url" value="{{ old("top_location_{$index}_url", $home->{"top_location_{$index}_url"} ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="Google Maps or page URL">
                    </div>
                @endforeach

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Facebook Link</label>
                    <input type="text" name="header_facebook_url" value="{{ old('header_facebook_url', $home->header_facebook_url ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="https://facebook.com/...">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">X / Twitter Link</label>
                    <input type="text" name="header_twitter_url" value="{{ old('header_twitter_url', $home->header_twitter_url ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="https://x.com/...">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Instagram Link</label>
                    <input type="text" name="header_instagram_url" value="{{ old('header_instagram_url', $home->header_instagram_url ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="https://instagram.com/...">
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-kasbitBlue hover:bg-blue-800 text-white rounded-lg shadow">
                        Save Header Settings
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center">
                    <i class="fa-solid fa-bars-staggered text-indigo-500 text-xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $editMenu ? 'Edit Header Item' : 'Add Header Item' }}
                    </h2>
                </div>
                @if($editMenu)
                    <a href="{{ route('header-menu.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                        Cancel
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ $editMenu ? route('header-menu.update', $editMenu) : route('header-menu.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @if($editMenu)
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $editMenu->name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="Home, About, Admissions">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link</label>
                    <input type="text" name="link" value="{{ old('link', $editMenu->link ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="/about or https://example.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Add Under Dropdown</label>
                    <select name="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent">
                        <option value="">Top Level Menu</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected((string) old('parent_id', $editMenu->parent_id ?? '') === (string) $parent->id)>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $editMenu->sort_order ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent">
                </div>

                <div class="md:col-span-2 flex items-center justify-between pt-2">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300" @checked(old('is_active', $editMenu->is_active ?? true))>
                        Show on frontend header
                    </label>
                    <button type="submit" class="px-5 py-2 bg-kasbitBlue hover:bg-blue-800 text-white rounded-lg shadow">
                        {{ $editMenu ? 'Update Item' : 'Add Item' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-800">Header Items</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Link</th>
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Order</th>
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr class="border-b">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $menu->name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $menu->link }}</td>
                                <td class="px-6 py-4">{{ $menu->sort_order }}</td>
                                <td class="px-6 py-4">{{ $menu->is_active ? 'Active' : 'Hidden' }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('header-menu.edit', $menu) }}" class="px-3 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('header-menu.destroy', $menu) }}" onsubmit="return confirm('Delete this menu item and its dropdown items?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @foreach($menu->children as $child)
                                <tr class="border-b bg-gray-50">
                                    <td class="px-6 py-4 pl-12 text-gray-800">- {{ $child->name }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $child->link }}</td>
                                    <td class="px-6 py-4">{{ $child->sort_order }}</td>
                                    <td class="px-6 py-4">{{ $child->is_active ? 'Active' : 'Hidden' }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('header-menu.edit', $child) }}" class="px-3 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('header-menu.destroy', $child) }}" onsubmit="return confirm('Delete this dropdown item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No header menu items yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
