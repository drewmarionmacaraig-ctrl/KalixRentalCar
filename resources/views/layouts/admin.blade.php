<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — Kalix Car Rental Palawan')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/kalix-icon.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="bg-[#F8F9FA] text-gray-900 min-h-screen flex">
    {{-- Mobile Backdrop --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    {{-- Dark Admin Sidebar (matching approved Admin 1440 design) --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:sticky lg:top-0 inset-y-0 left-0 z-50 w-60 bg-[#0A0A0A] text-gray-300 flex flex-col shrink-0 h-screen overflow-y-auto transition-transform duration-200">
        <div class="p-5 border-b border-white/10 flex items-center gap-3">
            <img src="{{ asset('images/kalix-icon.jpg') }}" alt="Kalix" class="w-9 h-9 rounded-full object-cover border border-red-600">
            <div>
                <div class="text-white font-black text-base tracking-tight leading-none">KALIX</div>
                <div class="text-[10px] font-bold text-red-500 uppercase tracking-widest mt-1">Admin Portal</div>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1 text-sm">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Dashboard'],
                    ['route' => 'admin.vehicles.index', 'match' => 'admin.vehicles.*', 'label' => 'Vehicles'],
                    ['route' => 'admin.bookings.index', 'match' => 'admin.bookings.*', 'label' => 'Bookings'],
                    ['route' => 'admin.inquiries.index', 'match' => 'admin.inquiries.*', 'label' => 'Inquiries'],
                    ['route' => 'admin.addons.index', 'match' => 'admin.addons.*', 'label' => 'Add-ons'],
                    ['route' => 'admin.payment-settings.edit', 'match' => 'admin.payment-settings.*', 'label' => 'Payment settings'],
                    ['route' => 'admin.reports.index', 'match' => 'admin.reports.*', 'label' => 'Reports'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center px-3.5 py-2.5 rounded-lg font-medium transition {{ $active ? 'bg-red-600 text-white font-semibold shadow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="p-4 border-t border-white/10 space-y-2 text-xs">
            <a href="{{ route('home') }}" target="_blank" class="block text-gray-400 hover:text-white transition">View Public Site &rarr;</a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-400 hover:text-red-300 font-semibold cursor-pointer">Sign out</button>
            </form>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar" class="lg:hidden p-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg sm:text-xl font-black text-gray-950 truncate">@yield('header', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                @yield('actions')
                <span class="hidden sm:inline text-xs font-medium text-gray-500 pl-3 border-l border-gray-200">
                    {{ auth()->user()->name ?? 'Admin' }}
                </span>
            </div>
        </header>

        <main class="flex-1 p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
