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
                            <h3 class="text-lg font-bold text-gray-800">Top Header Items</h3>
                            <p class="text-sm text-gray-500">Manage campus links, LMS and social icons shown above the main header.</p>
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox" name="top_header_is_active" value="1" @checked(old('top_header_is_active', $home->exists ? $home->top_header_is_active : true))>
                            Show top bar
                        </label>
                    </div>
                </div>

                @foreach([1, 2, 3, 4] as $index)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Top Item {{ $index }} Name</label>
                        <input type="text" name="top_location_{{ $index }}_name" value="{{ old("top_location_{$index}_name", $home->{"top_location_{$index}_name"} ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent" placeholder="{{ ['SMCHS', 'HYDERI', 'GULSHAN', 'LMS'][$index - 1] }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Top Item {{ $index }} Link</label>
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

            <form id="header-menu-form" method="POST" action="{{ $editMenu ? route('header-menu.update', $editMenu) : route('header-menu.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                @php
                    $selectedParentId = (string) old('parent_id', $editMenu->parent_id ?? request('parent_id', ''));
                    $selectedParent = $parents->firstWhere('id', (int) $selectedParentId);
                    $selectedParentLabel = $selectedParent
                        ? trim(($selectedParent->parent ? $selectedParent->parent->name . ' / ' : '') . $selectedParent->name)
                        : 'Top Level Menu';
                @endphp

                <div class="relative" data-parent-menu-picker>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Add Under Dropdown</label>
                    <button type="button"
                            class="w-full min-h-[42px] px-4 py-2 border border-gray-300 rounded-lg bg-white text-left flex items-center justify-between gap-3 focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                            data-parent-menu-trigger
                            aria-expanded="false">
                        <span class="truncate" data-parent-menu-label>{{ $selectedParentLabel }}</span>
                        <i class="fa-solid fa-chevron-down text-gray-500 text-xs shrink-0"></i>
                    </button>

                    <div class="hidden absolute left-0 right-0 top-full z-50 mt-2 rounded-xl border border-gray-200 bg-white shadow-xl overflow-hidden" data-parent-menu-panel>
                        <div class="p-3 border-b border-gray-100">
                            <input type="search"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                                   placeholder="Search menu..."
                                   data-parent-menu-search>
                        </div>
                        <div class="max-h-72 overflow-y-auto py-2 custom-scroll-hidden" data-parent-menu-options>
                            <button type="button"
                                    class="w-full px-4 py-2.5 text-left text-sm font-semibold text-kasbitBlue hover:bg-blue-50 flex items-center gap-2"
                                    data-parent-menu-option
                                    data-value=""
                                    data-label="Top Level Menu">
                                <i class="fa-solid fa-layer-group w-4"></i>
                                <span>Top Level Menu</span>
                            </button>
                            @foreach($parents as $parent)
                                @php
                                    $parentLabel = trim(($parent->parent ? $parent->parent->name . ' / ' : '') . $parent->name);
                                @endphp
                                <button type="button"
                                        class="w-full px-4 py-2.5 text-left text-sm hover:bg-gray-50 flex items-start gap-3 {{ (string) $parent->id === $selectedParentId ? 'bg-blue-50 text-kasbitBlue font-semibold' : 'text-gray-800' }}"
                                        data-parent-menu-option
                                        data-value="{{ $parent->id }}"
                                        data-label="{{ $parentLabel }}">
                                    <i class="fa-solid {{ $parent->parent ? 'fa-turn-up rotate-90 text-gray-400' : 'fa-folder text-yellow-500' }} mt-0.5 w-4 shrink-0"></i>
                                    <span class="leading-5">
                                        @if($parent->parent)
                                            <span class="block text-xs text-gray-500">{{ $parent->parent->name }}</span>
                                        @endif
                                        <span>{{ $parent->name }}</span>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <select name="parent_id" class="hidden" data-parent-menu-native>
                        <option value="">Top Level Menu</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected($selectedParentId === (string) $parent->id)>
                                {{ $parent->parent ? $parent->parent->name . ' → ' : '' }}{{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $editMenu->sort_order ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue focus:border-transparent">
                </div>

                @php
                    $iconOptions = \App\Http\Controllers\Admin\HeaderMenuController::iconOptions();
                    $selectedIcon = old('icon', $editMenu->icon ?? 'fa-solid fa-folder');
                    $selectedIconLabel = $iconOptions[$selectedIcon] ?? 'General / Folder';
                @endphp

                <div class="relative" data-icon-picker>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Sidebar Icon</label>
                    <button type="button"
                            class="w-full min-h-[42px] px-4 py-2 border border-gray-300 rounded-lg bg-white text-left flex items-center justify-between gap-3 focus:ring-2 focus:ring-kasbitBlue focus:border-transparent"
                            data-icon-trigger
                            aria-expanded="false">
                        <span class="flex min-w-0 items-center gap-2">
                            <i class="{{ $selectedIcon }} text-kasbitBlue w-4 shrink-0" data-icon-preview></i>
                            <span class="truncate" data-icon-label>{{ $selectedIconLabel }}</span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-gray-500 text-xs shrink-0"></i>
                    </button>

                    <div class="hidden absolute left-0 right-0 top-full z-50 mt-2 rounded-xl border border-gray-200 bg-white shadow-xl overflow-hidden" data-icon-panel>
                        <div class="max-h-72 overflow-y-auto p-2 custom-scroll-hidden">
                            @foreach($iconOptions as $icon => $label)
                                <button type="button"
                                        class="w-full px-3 py-2.5 text-left text-sm rounded-lg hover:bg-gray-50 flex items-center gap-3 {{ $selectedIcon === $icon ? 'bg-blue-50 text-kasbitBlue font-semibold' : 'text-gray-800' }}"
                                        data-icon-option
                                        data-value="{{ $icon }}"
                                        data-label="{{ $label }}">
                                    <i class="{{ $icon }} w-5 text-center shrink-0"></i>
                                    <span>{{ $label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <select name="icon" class="hidden" data-icon-native>
                        @foreach($iconOptions as $icon => $label)
                            <option value="{{ $icon }}" @selected(old('icon', $editMenu->icon ?? 'fa-solid fa-folder') === $icon)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">This icon appears in the Admin Website Sections menu.</p>
                </div>

                <div class="rounded-lg border border-indigo-100 bg-indigo-50 p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="show_in_admin_sidebar"
                               value="1"
                               class="mt-1 rounded border-gray-300"
                               @checked(old('show_in_admin_sidebar', $editMenu->show_in_admin_sidebar ?? false))>
                        <span>
                            <strong class="block text-sm text-gray-800">Show in Admin Website Sections</strong>
                            <span class="block text-xs text-gray-500 mt-1">The top-level item and its subcategories will appear in the admin sidebar.</span>
                        </span>
                    </label>
                </div>

                <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-4 pt-2">
                    <div class="flex flex-wrap gap-5">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300" @checked(old('is_active', $editMenu->is_active ?? true))>
                            Show on frontend header
                        </label>
                    </div>
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
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Admin Sidebar</th>
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-sm font-bold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr class="border-b align-middle">
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $menu->name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $menu->link }}</td>
                                <td class="px-6 py-4">{{ $menu->sort_order }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('header-menu.toggle', $menu) }}" data-admin-toggle>
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="field" value="show_in_admin_sidebar">
                                        <button type="submit" class="inline-flex items-center gap-2 font-semibold {{ $menu->show_in_admin_sidebar ? 'text-emerald-700' : 'text-gray-400' }}">
                                            <i class="{{ $menu->icon ?: 'fa-solid fa-folder' }}"></i>
                                            {{ $menu->show_in_admin_sidebar ? 'Visible' : 'Hidden' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('header-menu.toggle', $menu) }}" data-admin-toggle>
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="field" value="is_active">
                                        <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $menu->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="Toggle frontend status">
                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $menu->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                </td>
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
                                <tr class="border-b bg-gray-50 align-middle">
                                    <td class="px-6 py-4 pl-10 text-gray-800">
                                        <span class="inline-flex items-start gap-2">
                                            <span class="mt-2 h-px w-4 shrink-0 bg-gray-400"></span>
                                            <span>{{ $child->name }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 break-words">{{ $child->link }}</td>
                                    <td class="px-6 py-4">{{ $child->sort_order }}</td>
                                    <td class="px-6 py-4 text-gray-500">
                                        <i class="{{ $child->icon ?: 'fa-solid fa-circle' }} mr-2"></i>With parent
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('header-menu.toggle', $child) }}" data-admin-toggle>
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="field" value="is_active">
                                            <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $child->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="Toggle frontend status">
                                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $child->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                            </button>
                                        </form>
                                    </td>
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
                                        @foreach($child->children as $grandchild)
                                            <tr class="border-b bg-blue-50/50 align-middle">
                                                <td class="px-6 py-4 pl-16 text-gray-700">
                                                    <span class="inline-flex items-start gap-2">
                                                        <span class="mt-2 h-px w-5 shrink-0 bg-blue-300"></span>
                                                        <span>{{ $grandchild->name }}</span>
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 break-words">{{ $grandchild->link }}</td>
                                                <td class="px-6 py-4">{{ $grandchild->sort_order }}</td>
                                                <td class="px-6 py-4 text-blue-600">
                                                    <i class="fa-solid fa-code-branch mr-2"></i>Under {{ $child->name }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <form method="POST" action="{{ route('header-menu.toggle', $grandchild) }}" data-admin-toggle>
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="field" value="is_active">
                                                        <button type="submit" class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $grandchild->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" title="Toggle frontend status">
                                                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $grandchild->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="px-6 py-4 flex gap-2">
                                                    <a href="{{ route('header-menu.edit', $grandchild) }}" class="px-3 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 rounded-lg">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('header-menu.destroy', $grandchild) }}" onsubmit="return confirm('Delete this program item?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">No header menu items yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .custom-scroll-hidden {
            scrollbar-width: none;
        }

        .custom-scroll-hidden::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script data-admin-page-script>
        (() => {
            document.querySelectorAll('[data-parent-menu-picker]').forEach((picker) => {
                const trigger = picker.querySelector('[data-parent-menu-trigger]');
                const panel = picker.querySelector('[data-parent-menu-panel]');
                const search = picker.querySelector('[data-parent-menu-search]');
                const nativeSelect = picker.querySelector('[data-parent-menu-native]');
                const label = picker.querySelector('[data-parent-menu-label]');
                const options = Array.from(picker.querySelectorAll('[data-parent-menu-option]'));

                const close = () => {
                    panel.classList.add('hidden');
                    trigger.setAttribute('aria-expanded', 'false');
                };

                const open = () => {
                    panel.classList.remove('hidden');
                    trigger.setAttribute('aria-expanded', 'true');
                    search.value = '';
                    options.forEach((option) => option.classList.remove('hidden'));
                    setTimeout(() => search.focus(), 0);
                };

                trigger.addEventListener('click', () => {
                    panel.classList.contains('hidden') ? open() : close();
                });

                search.addEventListener('input', () => {
                    const query = search.value.trim().toLowerCase();
                    options.forEach((option) => {
                        option.classList.toggle('hidden', !option.dataset.label.toLowerCase().includes(query));
                    });
                });

                options.forEach((option) => {
                    option.addEventListener('click', () => {
                        nativeSelect.value = option.dataset.value;
                        label.textContent = option.dataset.label;
                        options.forEach((item) => item.classList.remove('bg-blue-50', 'text-kasbitBlue', 'font-semibold'));
                        option.classList.add('bg-blue-50', 'text-kasbitBlue', 'font-semibold');
                        close();
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!picker.contains(event.target)) {
                        close();
                    }
                });
            });

            document.querySelectorAll('[data-icon-picker]').forEach((picker) => {
                const trigger = picker.querySelector('[data-icon-trigger]');
                const panel = picker.querySelector('[data-icon-panel]');
                const nativeSelect = picker.querySelector('[data-icon-native]');
                const label = picker.querySelector('[data-icon-label]');
                const preview = picker.querySelector('[data-icon-preview]');
                const options = Array.from(picker.querySelectorAll('[data-icon-option]'));

                const close = () => {
                    panel.classList.add('hidden');
                    trigger.setAttribute('aria-expanded', 'false');
                };

                trigger.addEventListener('click', () => {
                    const willOpen = panel.classList.contains('hidden');
                    panel.classList.toggle('hidden', !willOpen);
                    trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                });

                options.forEach((option) => {
                    option.addEventListener('click', () => {
                        nativeSelect.value = option.dataset.value;
                        label.textContent = option.dataset.label;
                        preview.className = option.dataset.value + ' text-kasbitBlue w-4 shrink-0';
                        options.forEach((item) => item.classList.remove('bg-blue-50', 'text-kasbitBlue', 'font-semibold'));
                        option.classList.add('bg-blue-50', 'text-kasbitBlue', 'font-semibold');
                        close();
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!picker.contains(event.target)) {
                        close();
                    }
                });
            });
        })();
    </script>
</x-admin-layout>
