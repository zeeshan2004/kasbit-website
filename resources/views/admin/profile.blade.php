<x-admin-layout title="Admin Profile - KASBIT Control" header="Admin Profile">
    @php
        $profileInitials = collect(preg_split('/\s+/', trim($user->name ?? 'KASBIT Admin')) ?: [])
            ->filter()
            ->take(2)
            ->map(fn ($part) => str($part)->substr(0, 1)->upper())
            ->implode('') ?: 'KA';
    @endphp
    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <section class="bg-white rounded-lg shadow-md border-l-4 border-kasbitBlue p-6">
            <div class="flex items-center gap-4 mb-6">
                <span class="admin-user-avatar admin-user-avatar--profile" aria-hidden="true">{{ $profileInitials }}</span>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">KASBIT Admin</h1>
                    <p class="text-sm text-gray-500">Change login email and password from here.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue"
                               required>
                        @error('name')
                            <p class="admin-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Login Email / ID</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue"
                               required>
                        @error('email')
                            <p class="admin-field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="rounded-xl border border-amber-100 bg-amber-50 p-5">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fa-solid fa-key text-amber-600 mr-2"></i>Password
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                            <input type="password"
                                   name="current_password"
                                   autocomplete="current-password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                            @error('current_password')
                                <p class="admin-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                            <input type="password"
                                   name="password"
                                   autocomplete="new-password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                            @error('password')
                                <p class="admin-field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   autocomplete="new-password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kasbitBlue">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-kasbitBlue text-white rounded-lg hover:bg-blue-800 font-semibold">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>Save Profile
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-admin-layout>
