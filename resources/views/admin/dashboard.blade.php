<x-admin-layout>
    <x-slot name="title">
        Dashboard - KASBIT Control
    </x-slot>

    <x-slot name="header">
        System Overview
    </x-slot>

    <!-- STATS CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Pending Users Card -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-md text-white p-6 relative overflow-hidden transform hover:scale-[1.02] transition duration-200">
            <div class="relative z-10">
                <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Pending Users</p>
                <h3 class="text-4xl font-bold mt-2">2</h3>
            </div>
            <div class="absolute right-4 bottom-4 text-blue-500/30 text-6xl font-bold">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>

        <!-- Orders / Queries Card -->
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-xl shadow-md text-white p-6 relative overflow-hidden transform hover:scale-[1.02] transition duration-200">
            <div class="relative z-10">
                <p class="text-emerald-100 text-sm font-medium uppercase tracking-wider">Total Active Queries</p>
                <h3 class="text-4xl font-bold mt-2">12</h3>
            </div>
            <div class="absolute right-4 bottom-4 text-emerald-500/30 text-6xl font-bold">
                <i class="fa-solid fa-comments"></i>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-md text-white p-6 relative overflow-hidden transform hover:scale-[1.02] transition duration-200">
            <div class="relative z-10">
                <p class="text-amber-500 text-sm font-medium uppercase tracking-wider text-amber-50">Total Programs / Products</p>
                <h3 class="text-4xl font-bold mt-2">15</h3>
            </div>
            <div class="absolute right-4 bottom-4 text-amber-400/30 text-6xl font-bold">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>

    </div>

    <!-- BRIEF DATA TABLE OR SYSTEM INFO -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4 border-b pb-4">
            <h4 class="text-lg font-bold text-gray-800">Quick Actions & Recent Activity</h4>
            <span class="text-xs bg-kasbitBlue/10 text-kasbitBlue px-2.5 py-1 rounded-full font-semibold">Active Session</span>
        </div>
        <p class="text-gray-600 text-sm leading-relaxed">
            Welcome back! component architectural structural setup is ready. dynamic backend data directly controller variables ke through inject kar sakte hain.
        </p>
    </div>
</x-admin-layout>