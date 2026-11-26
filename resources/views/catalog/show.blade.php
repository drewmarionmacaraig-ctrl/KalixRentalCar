@extends('layouts.app')

@section('title', $vehicle->name . ' — Kalix Car Rental Palawan')

@section('content')
    @php
        $gallery = collect([$vehicle->image_url])->merge($vehicle->images->pluck('url'))->unique()->values();
    @endphp

    <div class="bg-[#F8F9FA] py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="text-xs text-gray-500 mb-6 flex items-center gap-2">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span>/</span>
                <a href="{{ route('catalog.index') }}" class="hover:text-gray-900">Vehicles</a>
                <span>/</span>
                <span class="text-gray-900 font-semibold">{{ $vehicle->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                {{-- Left Column: Gallery, Specs, Before you rent --}}
                <div class="lg:col-span-7 space-y-6">
                    <div x-data="{ activeImage: '{{ $gallery->first() }}' }" class="bg-white rounded-2xl border border-gray-200 overflow-hidden p-4 space-y-3">
                        <div class="relative aspect-[16/10] bg-gray-100 rounded-xl overflow-hidden">
                            <img :src="activeImage" alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
                            <div class="absolute top-3 left-3">
                                <x-status-badge :status="$vehicle->status" />
                            </div>
                        </div>

                        @if($gallery->count() > 1)
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($gallery as $img)
                                    <button type="button" @click="activeImage = '{{ $img }}'" class="aspect-[16/10] rounded-lg overflow-hidden border-2 transition cursor-pointer" :class="activeImage === '{{ $img }}' ? 'border-red-600' : 'border-transparent opacity-75 hover:opacity-100'">
                                        <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-6">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-red-600">
                                <span>{{ $vehicle->brand }}</span> &bull; <span>{{ $vehicle->category }}</span>
                            </div>
                            <h1 class="text-3xl font-black text-gray-950 mt-1">{{ $vehicle->name }}</h1>
                            <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $vehicle->description }}</p>
                        </div>

                        <div>
                            <h2 class="text-sm font-bold text-gray-950 mb-3">Specifications</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach([
                                    ['Transmission', $vehicle->transmission],
                                    ['Seats', $vehicle->seats . ' Passengers'],
                                    ['Fuel Type', $vehicle->fuel_type],
                                    ['Year Model', $vehicle->year],
                                    ['Plate / Unit', $vehicle->plate_number],
                                    ['Category', $vehicle->category],
                                ] as [$label, $val])
                                    <div class="bg-[#F8F9FA] rounded-xl border border-gray-200 p-3">
                                        <div class="text-[11px] font-medium text-gray-500">{{ $label }}</div>
                                        <div class="text-sm font-bold text-gray-950 mt-0.5">{{ $val }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <h2 class="text-sm font-bold text-gray-950 mb-3">Before you rent</h2>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start gap-2"><span class="text-red-600 font-bold">&bull;</span> Valid Driver's License &amp; Government ID required upon checkout and vehicle release.</li>
                                <li class="flex items-start gap-2"><span class="text-red-600 font-bold">&bull;</span> Down payment is non-refundable once reservation is confirmed; remaining balance is due on or before pickup.</li>
                                <li class="flex items-start gap-2"><span class="text-red-600 font-bold">&bull;</span> Vehicle must be returned at the agreed fuel level and cleanliness condition.</li>
                                <li class="flex items-start gap-2"><span class="text-red-600 font-bold">&bull;</span> Extensions require prior KLX approval before the original return time.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Right Sticky Booking Card --}}
                <div class="lg:col-span-5 lg:sticky lg:top-24">
                    <form action="{{ route('booking.checkout', $vehicle) }}" method="GET"
                        x-data="{
                            dailyRate: {{ (float) $vehicle->daily_rate }},
                            pickup: '{{ $prefillPickup }}',
                            ret: '{{ $prefillReturn }}',
                            selectedAddons: [],
                            addons: @js($addons->map(fn($a) => ['id' => $a->id, 'name' => $a->name, 'type' => $a->pricing_type, 'price' => (float) $a->price])),
                            get days() {
                                const p = new Date(this.pickup);
                                const r = new Date(this.ret);
                                if (isNaN(p) || isNaN(r) || r <= p) return 1;
                                const hrs = (r - p) / (1000 * 60 * 60);
                                return Math.max(1, Math.ceil(hrs / 24));
                            },
                            get baseTotal() {
                                return this.dailyRate * this.days;
                            },
                            get addonsTotal() {
                                return this.addons
                                    .filter(a => this.selectedAddons.includes(String(a.id)))
                                    .reduce((sum, a) => sum + (a.type === 'per_day' ? a.price * this.days : a.price), 0);
                            },
                            get grandTotal() {
                                return this.baseTotal + this.addonsTotal;
                            }
                        }"
                        class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-5">

                        <div class="flex items-baseline justify-between pb-4 border-b border-gray-100">
                            <div>
                                <span class="text-3xl font-black text-gray-950">₱{{ number_format((float) $vehicle->daily_rate, 0) }}</span>
                                <span class="text-sm text-gray-500">/ day</span>
                            </div>
                            <x-status-badge :status="$vehicle->status" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pickup Date & Time</label>
                                <input type="datetime-local" name="pickup_datetime" x-model="pickup" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Return Date & Time</label>
                                <input type="datetime-local" name="return_datetime" x-model="ret" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                        </div>

                        @if($addons->isNotEmpty())
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Optional Add-ons</label>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                    @foreach($addons as $addon)
                                        <label class="flex items-center justify-between gap-3 p-2.5 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer text-xs">
                                            <span class="flex items-center gap-2.5">
                                                <input type="checkbox" name="addons[]" value="{{ $addon->id }}" x-model="selectedAddons" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                                                <span class="font-semibold text-gray-900">{{ $addon->name }}</span>
                                            </span>
                                            <span class="font-bold text-gray-700 shrink-0">{{ $addon->formatted_price }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="bg-[#F8F9FA] rounded-xl p-4 space-y-2 text-sm border border-gray-200">
                            <div class="flex justify-between text-gray-600">
                                <span>₱{{ number_format((float) $vehicle->daily_rate, 0) }} &times; <span x-text="days"></span> day(s)</span>
                                <span class="font-semibold text-gray-900">₱<span x-text="baseTotal.toLocaleString()"></span></span>
                            </div>
                            <div class="flex justify-between text-gray-600" x-show="addonsTotal > 0">
                                <span>Add-ons</span>
                                <span class="font-semibold text-gray-900">₱<span x-text="addonsTotal.toLocaleString()"></span></span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 flex justify-between font-black text-gray-950 text-base">
                                <span>Estimated Total</span>
                                <span>₱<span x-text="grandTotal.toLocaleString()"></span></span>
                            </div>
                        </div>

                        @if($vehicle->status === 'maintenance')
                            <button type="button" disabled class="w-full py-3 rounded-xl bg-gray-200 text-gray-500 font-semibold text-sm cursor-not-allowed">
                                Currently Under Maintenance
                            </button>
                        @else
                            <button type="submit" class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-sm transition cursor-pointer">
                                Book now
                            </button>
                        @endif

                        <a href="{{ route('inquiry.create', ['vehicle_id' => $vehicle->id]) }}" class="block text-center text-xs font-semibold text-gray-600 hover:text-red-600 transition">
                            Have a question about this vehicle? Send an inquiry
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
