@extends('layouts.admin')

@section('header', 'Extend rental — ' . $booking->booking_code)

@section('content')
    @if($conflict)
        <div class="mb-6 rounded-xl bg-red-50 border border-red-300 p-4 text-sm text-red-800 flex items-start gap-3">
            <span class="font-black text-red-600">CONFLICT:</span>
            <div>
                <strong>{{ $booking->vehicle->name }}</strong> already has booking
                <strong>{{ $conflict->booking_code }}</strong> ({{ $conflict->renter_name }}) from
                <strong>{{ $conflict->pickup_datetime->format('M d, Y g:i A') }}</strong> to
                <strong>{{ $conflict->return_datetime->format('M d, Y g:i A') }}</strong>.
                Choose an earlier return time to avoid double-booking.
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-7 bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
            <h2 class="font-bold text-gray-950 text-base">Extension details</h2>

            {{-- Check Availability Form (GET) --}}
            <form action="{{ route('admin.bookings.extend', $booking) }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end pb-5 border-b border-gray-100">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Proposed New Return Date &amp; Time</label>
                    <input type="datetime-local" name="new_return_datetime" value="{{ $proposedReturn->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                </div>
                <button type="submit" class="py-2 px-4 rounded-lg bg-gray-950 hover:bg-gray-800 text-white text-xs font-semibold cursor-pointer">
                    Check availability
                </button>
            </form>

            {{-- Confirm Extension Form (POST) --}}
            <form action="{{ route('admin.bookings.extend.store', $booking) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="new_return_datetime" value="{{ $proposedReturn->format('Y-m-d\TH:i') }}">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Extension Daily Rate (₱ / day)</label>
                    <input type="number" name="rate_per_day" value="{{ $ratePerDay }}" step="50" min="0" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" placeholder="Optional extension notes..." class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" @disabled((bool) $conflict || $extraDays <= 0)
                        class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition {{ $conflict || $extraDays <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700 cursor-pointer' }}">
                        Confirm extension
                    </button>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-semibold">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="lg:col-span-5 bg-white rounded-2xl border border-gray-200 p-6 space-y-3 text-sm">
            <h2 class="font-bold text-gray-950 text-base">Extension summary</h2>
            <div class="flex justify-between text-xs text-gray-600">
                <span>Current Return</span>
                <span class="font-semibold text-gray-900">{{ $booking->return_datetime->format('M d, Y g:i A') }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-600">
                <span>New Return</span>
                <span class="font-semibold text-gray-900">{{ $proposedReturn->format('M d, Y g:i A') }}</span>
            </div>
            <div class="flex justify-between text-xs text-gray-600">
                <span>Extra Days</span>
                <span class="font-bold text-gray-950">{{ $extraDays }} day(s)</span>
            </div>
            <div class="pt-3 border-t border-gray-200 flex justify-between font-black text-gray-950 text-base">
                <span>Added Charge</span>
                <span class="text-red-600">+₱{{ number_format($addedAmount, 2) }}</span>
            </div>
        </div>
    </div>
@endsection
