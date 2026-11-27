@extends('layouts.app')

@section('title', 'Kalix Car Rental Palawan — Drive More, Explore More')

@section('content')
    {{-- Dark Hero Section --}}
    <section class="bg-[#0A0A0A] text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-600/15 border border-red-600/30 text-red-400 text-xs font-semibold uppercase tracking-wider">
                        Puerto Princesa &bull; Palawan
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.05]">
                        Drive more.<br>
                        <span class="text-red-600">Explore more.</span>
                    </h1>
                    <p class="text-gray-300 text-base sm:text-lg max-w-xl leading-relaxed">
                        Reliable, clean, and affordable cars for rent across Palawan. Transparent daily rates, flexible add-ons, and digital contract signing in minutes.
                    </p>
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('catalog.index') }}" class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-lg shadow-red-600/25 transition">
                            Browse vehicles
                        </a>
                        <a href="{{ route('inquiry.create') }}" class="px-6 py-3 rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 text-white font-semibold text-sm transition">
                            Send an inquiry
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-[#141414] border border-white/10 rounded-2xl p-4 shadow-2xl">
                        <img src="{{ asset('images/kalix-logo.webp') }}" alt="KLX Affordable Car Rental Palawan" class="w-full rounded-xl object-contain bg-white p-3">
                        <div class="mt-4 grid grid-cols-3 gap-3 text-center text-xs">
                            <div class="bg-white/5 rounded-lg p-2.5 border border-white/10">
                                <div class="text-white font-bold text-sm">Self-Drive</div>
                                <div class="text-gray-400 mt-0.5">or With Driver</div>
                            </div>
                            <div class="bg-white/5 rounded-lg p-2.5 border border-white/10">
                                <div class="text-white font-bold text-sm">PPS Airport</div>
                                <div class="text-gray-400 mt-0.5">Delivery Ready</div>
                            </div>
                            <div class="bg-white/5 rounded-lg p-2.5 border border-white/10">
                                <div class="text-white font-bold text-sm">24/7 Support</div>
                                <div class="text-gray-400 mt-0.5">Local Team</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Availability Search Bar --}}
            <form action="{{ route('catalog.index') }}" method="GET" class="mt-12 bg-white text-gray-900 rounded-2xl p-4 sm:p-5 shadow-xl grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pickup Date & Time</label>
                    <input type="datetime-local" name="pickup_datetime" value="{{ now()->addDay()->setTime(9, 0)->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Return Date & Time</label>
                    <input type="datetime-local" name="return_datetime" value="{{ now()->addDays(3)->setTime(9, 0)->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Category</label>
                    <select name="category" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                        <option value="">All vehicle types</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="hidden" name="available_only" value="1">
                    <button type="submit" class="w-full py-2.5 px-5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition cursor-pointer">
                        Search available cars
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- Ready-to-go rides --}}
    <section class="py-16 lg:py-20 bg-[#F8F9FA]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-red-600">Our Fleet</span>
                    <h2 class="text-3xl font-black text-gray-950 mt-1">Ready-to-go rides</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700 inline-flex items-center gap-1">
                    View all vehicles &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredVehicles as $vehicle)
                    <x-vehicle-card :vehicle="$vehicle" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Four steps to your trip --}}
    <section id="how-it-works" class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-red-600">Simple Process</span>
                <h2 class="text-3xl font-black text-gray-950 mt-1">Four steps to your trip</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['1', 'Choose your vehicle', 'Browse our inspected sedans, MPVs, SUVs, and vans with real-time availability.'],
                    ['2', 'Pick dates & add-ons', 'Select your pickup/return schedule, location, and optional driver or child seat.'],
                    ['3', 'Sign contract & pay', 'Review the KLX Rental Agreement online, sign digitally, and upload payment proof.'],
                    ['4', 'Pick up & drive', 'Receive your verified confirmation & PDF contract, then hit the Palawan roads.'],
                ] as [$num, $title, $desc])
                    <div class="bg-[#F8F9FA] rounded-2xl border border-gray-200 p-6 space-y-3">
                        <div class="w-9 h-9 rounded-xl bg-red-600 text-white font-black text-sm flex items-center justify-center">
                            {{ $num }}
                        </div>
                        <h3 class="font-bold text-gray-950 text-base">{{ $title }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Dark Value Prop Section --}}
    <section class="bg-[#0A0A0A] text-white py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-5 space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-red-500">Why Kalix Palawan</span>
                <h2 class="text-3xl sm:text-4xl font-black leading-tight">
                    Local team. Honest rates. Cars you can rely on.
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Based at Foton Tagburos, Puerto Princesa, we maintain every unit in-house and provide clear terms with zero hidden fees.
                </p>
            </div>
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach([
                    ['Transparent Pricing', 'Clear daily rates, itemized add-ons, and flexible down payment or full payment options.'],
                    ['Digital Rental Agreement', 'Instant, downloadable 2-page PDF contract with vehicle release & return checklist.'],
                    ['Flexible Payment Options', 'Pay conveniently via GCash, PalawanPay, bank transfer, or credit card.'],
                    ['Airport & Hotel Delivery', 'Schedule pickup at Foton Tagburos or request delivery within Puerto Princesa.'],
                ] as [$title, $desc])
                    <div class="bg-[#141414] border border-white/10 rounded-xl p-5 space-y-2">
                        <h3 class="font-bold text-white text-sm">{{ $title }}</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Red CTA Banner --}}
    <section class="bg-red-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black">Ready to explore Palawan?</h2>
                <p class="text-red-100 text-sm mt-1">Reserve your ride online today or message us at 0907-883-8861.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('catalog.index') }}" class="px-6 py-3 rounded-xl bg-black hover:bg-gray-900 text-white font-semibold text-sm transition">
                    Book a car
                </a>
                <a href="{{ route('inquiry.create') }}" class="px-6 py-3 rounded-xl bg-white text-red-600 hover:bg-red-50 font-semibold text-sm transition">
                    Contact us
                </a>
            </div>
        </div>
    </section>
@endsection
