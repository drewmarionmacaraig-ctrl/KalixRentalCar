@extends('layouts.app')

@section('title', 'Send an Inquiry — Kalix Car Rental Palawan')

@section('content')
    <div class="bg-[#F8F9FA] py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-950">Send us an inquiry</h1>
                <p class="text-sm text-gray-600 mt-1">Need a custom quote, long-term rental, or airport pickup? Message our team.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-7 bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-950 mb-5">Tell us what you need</h2>

                    <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Contact No. (SMS/Viber/WhatsApp) *</label>
                                <input type="text" name="contact_no" value="{{ old('contact_no') }}" required placeholder="09XX-XXX-XXXX" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                                @error('contact_no') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Preferred Vehicle (Optional)</label>
                                <select name="vehicle_id" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                                    <option value="">Any / Not sure yet</option>
                                    @foreach($vehicles as $v)
                                        <option value="{{ $v->id }}" @selected(old('vehicle_id', $selectedVehicleId) == $v->id)>{{ $v->name }} (₱{{ number_format((float) $v->daily_rate, 0) }}/day)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Subject *</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="e.g. 5-day El Nido trip quote" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                            @error('subject') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Message *</label>
                            <textarea name="message" rows="4" required placeholder="Share your travel dates, pickup location, and any special requests..." class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">{{ old('message') }}</textarea>
                            @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition cursor-pointer">
                            Send inquiry
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                        <h2 class="text-lg font-bold text-gray-950">Contact us</h2>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase">Garage / Pickup Point</div>
                                <div class="font-semibold text-gray-900 mt-0.5">Foton Tagburos, Puerto Princesa, Palawan</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase">SMS / Viber / WhatsApp</div>
                                <div class="font-bold text-red-600 text-base mt-0.5">0907-883-8861</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase">Operating Hours</div>
                                <div class="font-medium text-gray-900 mt-0.5">Monday – Sunday: 6:00 AM – 9:00 PM</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#0A0A0A] text-white rounded-2xl p-6 space-y-3">
                        <h3 class="font-bold text-base">Need a car today?</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">Browse our live fleet availability and complete your reservation online in under 5 minutes.</p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
                            Browse available cars
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
