@extends('layouts.app')

@section('title', 'Reserve ' . $vehicle->name . ' — Kalix Car Rental Palawan')

@section('content')
    <x-booking-stepper :currentStep="1" />

    <div class="bg-[#F8F9FA] py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-950">Reserve your vehicle</h1>
                <p class="text-sm text-gray-600 mt-1">Complete your renter, driver, and trip details below.</p>
            </div>

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('booking.checkout.store', $vehicle) }}" method="POST" enctype="multipart/form-data"
                x-data="{
                    dailyRate: {{ (float) $vehicle->daily_rate }},
                    pickup: '{{ old('pickup_datetime', $pickup->format('Y-m-d\TH:i')) }}',
                    ret: '{{ old('return_datetime', $return->format('Y-m-d\TH:i')) }}',
                    selectedAddons: @js(array_map('strval', (array) old('addons', $selectedAddonIds))),
                    addons: @js($addons->map(fn($a) => ['id' => $a->id, 'name' => $a->name, 'type' => $a->pricing_type, 'price' => (float) $a->price])),
                    paymentOption: '{{ old('payment_option', 'down_payment') }}',
                    paymentMethod: '{{ old('payment_method', $paymentMethods->first()?->code ?? 'credit_card') }}',
                    dpType: '{{ $settings->down_payment_type }}',
                    dpPercent: {{ (float) $settings->down_payment_percentage }},
                    dpFixed: {{ (float) $settings->down_payment_fixed_amount }},
                    ccFeePercent: {{ (float) $settings->credit_card_fee_percentage }},
                    get days() {
                        const p = new Date(this.pickup);
                        const r = new Date(this.ret);
                        if (isNaN(p) || isNaN(r) || r <= p) return 1;
                        return Math.max(1, Math.ceil(((r - p) / 3600000) / 24));
                    },
                    get baseAmount() {
                        return this.dailyRate * this.days;
                    },
                    get addonsAmount() {
                        return this.addons
                            .filter(a => this.selectedAddons.includes(String(a.id)))
                            .reduce((sum, a) => sum + (a.type === 'per_day' ? a.price * this.days : a.price), 0);
                    },
                    get processingFee() {
                        if (this.paymentMethod !== 'credit_card') return 0;
                        return Math.round((this.baseAmount + this.addonsAmount) * (this.ccFeePercent / 100) * 100) / 100;
                    },
                    get totalAmount() {
                        return this.baseAmount + this.addonsAmount + this.processingFee;
                    },
                    get downPaymentAmount() {
                        if (this.dpType === 'fixed') return Math.min(this.totalAmount, this.dpFixed);
                        return Math.round(this.totalAmount * (this.dpPercent / 100) * 100) / 100;
                    },
                    get amountDueNow() {
                        return this.paymentOption === 'full' ? this.totalAmount : this.downPaymentAmount;
                    },
                    get remainingBalance() {
                        return Math.max(0, this.totalAmount - this.amountDueNow);
                    }
                }"
                class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                @csrf

                <div class="lg:col-span-8 space-y-6">
                    {{-- Renter Details --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                        <h2 class="text-base font-bold text-gray-950">Renter details</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Renter Full Name *</label>
                                <input type="text" name="renter_name" value="{{ old('renter_name') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Contact No. *</label>
                                <input type="text" name="contact_no" value="{{ old('contact_no') }}" required placeholder="09XX-XXX-XXXX" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Driver's License No. *</label>
                                <input type="text" name="drivers_license_no" value="{{ old('drivers_license_no') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Government ID No. *</label>
                                <input type="text" name="government_id_no" value="{{ old('government_id_no') }}" required placeholder="Passport / UMID / PhilSys / National ID" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>

                            {{-- Driver's License Image Upload --}}
                            <div x-data="{ preview: '', fileName: '' }">
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Driver's License Photo *</label>
                                <div class="mb-2 aspect-[16/10] rounded-xl border border-gray-200 bg-[#F8F9FA] flex items-center justify-center overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" alt="Driver's License Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <span class="text-[11px] font-medium text-gray-400">Driver's License Preview</span>
                                    </template>
                                </div>
                                <label class="flex items-center justify-between gap-2 rounded-xl border border-dashed border-gray-300 hover:border-red-500 bg-[#F8F9FA] px-3 py-2.5 cursor-pointer transition">
                                    <span class="text-xs text-gray-600 truncate" x-text="fileName || 'Upload Driver\'s License...'"></span>
                                    <span class="px-3 py-1 rounded-lg bg-red-600 text-white text-xs font-semibold shrink-0">Choose Image</span>
                                    <input type="file" name="drivers_license_image" accept="image/*" required @change="if($event.target.files[0]) { fileName = $event.target.files[0].name; preview = URL.createObjectURL($event.target.files[0]); }" class="sr-only">
                                </label>
                            </div>

                            {{-- Government ID Image Upload --}}
                            <div x-data="{ preview: '', fileName: '' }">
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Government ID Photo *</label>
                                <div class="mb-2 aspect-[16/10] rounded-xl border border-gray-200 bg-[#F8F9FA] flex items-center justify-center overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" alt="Government ID Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!preview">
                                        <span class="text-[11px] font-medium text-gray-400">Government ID Preview</span>
                                    </template>
                                </div>
                                <label class="flex items-center justify-between gap-2 rounded-xl border border-dashed border-gray-300 hover:border-red-500 bg-[#F8F9FA] px-3 py-2.5 cursor-pointer transition">
                                    <span class="text-xs text-gray-600 truncate" x-text="fileName || 'Upload Government ID...'"></span>
                                    <span class="px-3 py-1 rounded-lg bg-red-600 text-white text-xs font-semibold shrink-0">Choose Image</span>
                                    <input type="file" name="government_id_image" accept="image/*" required @change="if($event.target.files[0]) { fileName = $event.target.files[0].name; preview = URL.createObjectURL($event.target.files[0]); }" class="sr-only">
                                </label>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Authorized Drivers (if different from renter)</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Primary Driver Name</label>
                                    <input type="text" name="primary_driver_name" value="{{ old('primary_driver_name') }}" placeholder="Defaults to Renter Name" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Primary Driver License No.</label>
                                    <input type="text" name="primary_driver_license" value="{{ old('primary_driver_license') }}" placeholder="Defaults to Renter License" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Additional Driver Name (Optional)</label>
                                    <input type="text" name="additional_driver_name" value="{{ old('additional_driver_name') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Additional Driver License No.</label>
                                    <input type="text" name="additional_driver_license" value="{{ old('additional_driver_license') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Trip Details --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                        <h2 class="text-base font-bold text-gray-950">Trip details</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Rental Start Date & Time *</label>
                                <input type="datetime-local" name="pickup_datetime" x-model="pickup" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Return Date & Time *</label>
                                <input type="datetime-local" name="return_datetime" x-model="ret" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pickup Location *</label>
                                <input type="text" name="pickup_location" value="{{ old('pickup_location', 'Foton Tagburos, Puerto Princesa') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Return / Delivery Location *</label>
                                <input type="text" name="return_location" value="{{ old('return_location', 'Foton Tagburos, Puerto Princesa') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Route / Intended Use</label>
                                <input type="text" name="route_intended_use" value="{{ old('route_intended_use', 'Puerto Princesa / Palawan Tour') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                            </div>
                        </div>
                    </div>

                    {{-- Add-ons --}}
                    @if($addons->isNotEmpty())
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                            <h2 class="text-base font-bold text-gray-950">Add-ons</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($addons as $addon)
                                    <label class="flex items-start justify-between gap-3 p-3 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer">
                                        <span class="flex items-start gap-2.5">
                                            <input type="checkbox" name="addons[]" value="{{ $addon->id }}" x-model="selectedAddons" class="mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-600">
                                            <span>
                                                <span class="block text-sm font-semibold text-gray-900">{{ $addon->name }}</span>
                                                @if($addon->description)
                                                    <span class="block text-xs text-gray-500 mt-0.5">{{ $addon->description }}</span>
                                                @endif
                                            </span>
                                        </span>
                                        <span class="text-xs font-bold text-red-600 shrink-0">{{ $addon->formatted_price }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Payment Option & Method --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
                        <div>
                            <h2 class="text-base font-bold text-gray-950 mb-3">Payment option</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition" :class="paymentOption === 'down_payment' ? 'border-red-600 bg-red-50/40' : 'border-gray-200'">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="payment_option" value="down_payment" x-model="paymentOption" class="text-red-600 focus:ring-red-600">
                                        <span>
                                            <span class="block text-sm font-bold text-gray-950">Down payment</span>
                                            <span class="block text-xs text-gray-500">
                                                {{ $settings->down_payment_type === 'fixed' ? 'Fixed ₱' . number_format((float) $settings->down_payment_fixed_amount, 0) : number_format((float) $settings->down_payment_percentage, 0) . '% of total' }} (Non-refundable)
                                            </span>
                                        </span>
                                    </span>
                                    <span class="text-sm font-black text-red-600">₱<span x-text="downPaymentAmount.toLocaleString()"></span></span>
                                </label>

                                <label class="flex items-center justify-between p-4 rounded-xl border-2 cursor-pointer transition" :class="paymentOption === 'full' ? 'border-red-600 bg-red-50/40' : 'border-gray-200'">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="payment_option" value="full" x-model="paymentOption" class="text-red-600 focus:ring-red-600">
                                        <span>
                                            <span class="block text-sm font-bold text-gray-950">Full payment</span>
                                            <span class="block text-xs text-gray-500">Settle 100% upfront</span>
                                        </span>
                                    </span>
                                    <span class="text-sm font-black text-gray-950">₱<span x-text="totalAmount.toLocaleString()"></span></span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-base font-bold text-gray-950 mb-3">Payment method</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach($paymentMethods as $pm)
                                    <label class="p-3 rounded-xl border-2 text-center cursor-pointer transition text-xs font-bold" :class="paymentMethod === '{{ $pm->code }}' ? 'border-red-600 bg-red-50/40 text-red-700' : 'border-gray-200 text-gray-700'">
                                        <input type="radio" name="payment_method" value="{{ $pm->code }}" x-model="paymentMethod" class="sr-only">
                                        {{ $pm->name }}
                                    </label>
                                @endforeach
                                @if($settings->credit_card_enabled)
                                    <label class="p-3 rounded-xl border-2 text-center cursor-pointer transition text-xs font-bold" :class="paymentMethod === 'credit_card' ? 'border-red-600 bg-red-50/40 text-red-700' : 'border-gray-200 text-gray-700'">
                                        <input type="radio" name="payment_method" value="credit_card" x-model="paymentMethod" class="sr-only">
                                        Credit Card (+{{ number_format((float) $settings->credit_card_fee_percentage, 0) }}%)
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm shadow-sm transition cursor-pointer">
                        Continue to Rental Agreement &rarr;
                    </button>
                </div>

                {{-- Sticky Booking Summary --}}
                <div class="lg:col-span-4 lg:sticky lg:top-24">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
                        <h2 class="text-base font-bold text-gray-950">Booking summary</h2>
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                            <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" class="w-16 h-12 rounded-lg object-cover bg-gray-100 shrink-0">
                            <div>
                                <div class="font-bold text-gray-950 text-sm">{{ $vehicle->name }}</div>
                                <div class="text-xs text-gray-500">{{ $vehicle->plate_number }} &bull; {{ $vehicle->transmission }}</div>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Base rate (<span x-text="days"></span>d &times; ₱{{ number_format((float) $vehicle->daily_rate, 0) }})</span>
                                <span class="font-semibold text-gray-900">₱<span x-text="baseAmount.toLocaleString()"></span></span>
                            </div>
                            <div class="flex justify-between text-gray-600" x-show="addonsAmount > 0">
                                <span>Add-ons</span>
                                <span class="font-semibold text-gray-900">₱<span x-text="addonsAmount.toLocaleString()"></span></span>
                            </div>
                            <div class="flex justify-between text-gray-600" x-show="processingFee > 0">
                                <span>Credit Card Fee ({{ number_format((float) $settings->credit_card_fee_percentage, 0) }}%)</span>
                                <span class="font-semibold text-gray-900">₱<span x-text="processingFee.toLocaleString()"></span></span>
                            </div>
                            <div class="pt-2 border-t border-gray-200 flex justify-between font-black text-gray-950 text-base">
                                <span>Total</span>
                                <span>₱<span x-text="totalAmount.toLocaleString()"></span></span>
                            </div>
                            <div class="bg-[#F8F9FA] rounded-xl p-3 border border-gray-200 space-y-1 text-xs mt-2">
                                <div class="flex justify-between font-bold text-red-600">
                                    <span>Due Now</span>
                                    <span>₱<span x-text="amountDueNow.toLocaleString()"></span></span>
                                </div>
                                <div class="flex justify-between text-gray-600" x-show="remainingBalance > 0">
                                    <span>Remaining Balance (on pickup)</span>
                                    <span class="font-semibold text-gray-900">₱<span x-text="remainingBalance.toLocaleString()"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
