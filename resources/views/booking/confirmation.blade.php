@extends('layouts.app')

@section('title', 'Reservation Received — ' . $booking->booking_code)

@section('content')
    <x-booking-stepper :currentStep="4" />

    <div class="bg-[#F8F9FA] py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-black shrink-0">
                            &#10003;
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <h1 class="text-2xl font-black text-gray-950">Reservation received</h1>
                                <x-status-badge :status="$booking->status" />
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                We've received your payment proof for booking <strong class="text-gray-950">{{ $booking->booking_code }}</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-red-700 uppercase">Booking Reference Code</div>
                            <div class="text-xl font-black text-red-700 tracking-wide">{{ $booking->booking_code }}</div>
                        </div>
                        <a href="{{ route('booking.contract.pdf', $booking) }}" class="px-4 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
                            Download Contract PDF
                        </a>
                    </div>

                    <div class="space-y-3 text-sm text-gray-700">
                        <h2 class="font-bold text-gray-950">What happens next?</h2>
                        <ol class="list-decimal pl-5 space-y-2 text-xs sm:text-sm">
                            <li>Our team at <strong>Foton Tagburos, Puerto Princesa</strong> will verify your payment proof shortly.</li>
                            <li>Once verified, your booking status will be updated to <strong>Confirmed</strong> and we'll message you via SMS/Viber/WhatsApp ({{ $booking->contact_no }}).</li>
                            <li>Bring your original <strong>Driver's License</strong> and <strong>Government ID</strong> upon vehicle release.</li>
                        </ol>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-3">
                        <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl bg-gray-950 hover:bg-gray-800 text-white text-sm font-semibold transition">
                            Back to Home
                        </a>
                        <a href="{{ route('catalog.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold transition">
                            Browse more vehicles
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    @include('booking.partials.summary', ['booking' => $booking])
                </div>
            </div>
        </div>
    </div>
@endsection
