<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kalix Car Rental Palawan — Drive More, Explore More')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/kalix-icon.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-900">
    {{-- Top Info Bar --}}
    <div class="bg-[#0A0A0A] text-gray-300 text-xs border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-9 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                    Foton Tagburos, Puerto Princesa, Palawan
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span>SMS / Viber / WhatsApp: <strong class="text-white">0907-883-8861</strong></span>
                <a href="{{ route('admin.login') }}" class="hidden sm:inline text-gray-400 hover:text-white transition">Admin</a>
            </div>
        </div>
    </div>

    {{-- Main Navbar --}}
    <header x-data="{ mobileOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/kalix-icon.jpg') }}" alt="Kalix Car Rental" class="w-10 h-10 rounded-full object-cover border border-red-600/30">
                <div class="leading-tight">
                    <span class="block text-lg font-black tracking-tight text-gray-950">KALIX</span>
                    <span class="block text-[10px] font-bold tracking-widest uppercase text-red-600">Car Rental Palawan</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 text-sm font-medium">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-red-600 font-semibold' : 'text-gray-600 hover:text-gray-950' }} transition">Home</a>
                <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*') ? 'text-red-600 font-semibold' : 'text-gray-600 hover:text-gray-950' }} transition">Browse vehicles</a>
                <a href="{{ route('home') }}#how-it-works" class="text-gray-600 hover:text-gray-950 transition">How it works</a>
                <a href="{{ route('inquiry.create') }}" class="{{ request()->routeIs('inquiry.*') ? 'text-red-600 font-semibold' : 'text-gray-600 hover:text-gray-950' }} transition">Contact / Inquiry</a>
            </nav>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold shadow-sm transition">
                    Book now
                </a>
                <button type="button" @click="mobileOpen = !mobileOpen" aria-label="Toggle menu" class="md:hidden p-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition class="md:hidden border-t border-gray-200 bg-white px-4 py-3 space-y-1 text-sm font-medium">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'bg-red-50 text-red-600 font-semibold' : 'text-gray-700' }}">Home</a>
            <a href="{{ route('catalog.index') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('catalog.*') ? 'bg-red-50 text-red-600 font-semibold' : 'text-gray-700' }}">Browse vehicles</a>
            <a href="{{ route('home') }}#how-it-works" @click="mobileOpen = false" class="block px-3 py-2 rounded-lg text-gray-700">How it works</a>
            <a href="{{ route('inquiry.create') }}" class="block px-3 py-2 rounded-lg {{ request()->routeIs('inquiry.*') ? 'bg-red-50 text-red-600 font-semibold' : 'text-gray-700' }}">Contact / Inquiry</a>
            <a href="{{ route('admin.login') }}" class="block px-3 py-2 rounded-lg text-gray-500">Admin Portal</a>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Dark Footer --}}
    <footer class="bg-[#0A0A0A] text-gray-400 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/kalix-icon.jpg') }}" alt="Kalix Car Rental" class="w-11 h-11 rounded-full object-cover border border-red-600">
                    <div>
                        <div class="text-white font-black text-lg tracking-tight">KALIX</div>
                        <div class="text-xs text-red-500 font-semibold uppercase tracking-wider">Affordable Cars for Rent – Palawan</div>
                    </div>
                </div>
                <p class="text-sm text-gray-400 max-w-md leading-relaxed">
                    Drive more. Explore more. Reliable, well-maintained self-drive and chauffeur-driven vehicles for Puerto Princesa, Sabang, San Vicente, and El Nido trips.
                </p>
            </div>

            <div>
                <h3 class="text-white font-semibold text-sm mb-3">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-white transition">Browse Vehicles</a></li>
                    <li><a href="{{ route('inquiry.create') }}" class="hover:text-white transition">Send an Inquiry</a></li>
                    <li><a href="{{ route('admin.login') }}" class="hover:text-white transition">Admin Portal</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold text-sm mb-3">Contact Us</h3>
                <ul class="space-y-2 text-sm">
                    <li>Foton Tagburos, Puerto Princesa City, Palawan</li>
                    <li>SMS / Viber / WhatsApp: <span class="text-white font-medium">0907-883-8861</span></li>
                    <li>Open Daily: 6:00 AM – 9:00 PM</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 py-5 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} KLX Affordable Cars for Rent – Palawan. All rights reserved.
        </div>
    </footer>
</body>
</html>
