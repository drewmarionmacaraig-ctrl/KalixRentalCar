<div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-4">
    <h2 class="text-base font-bold text-gray-950">Booking summary</h2>

    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
        <img src="{{ $booking->vehicle->image_url }}" alt="{{ $booking->vehicle->name }}" class="w-16 h-12 rounded-lg object-cover bg-gray-100 shrink-0">
        <div>
            <div class="font-bold text-gray-950 text-sm">{{ $booking->vehicle->name }}</div>
            <div class="text-xs text-gray-500">{{ $booking->vehicle->plate_number }} &bull; {{ $booking->vehicle->transmission }}</div>
        </div>
    </div>

    <div class="space-y-2 text-xs text-gray-600 pb-4 border-b border-gray-100">
        <div class="flex justify-between">
            <span>Booking Code</span>
            <span class="font-bold text-gray-950">{{ $booking->booking_code }}</span>
        </div>
        <div class="flex justify-between">
            <span>Pickup</span>
            <span class="font-semibold text-gray-900">{{ $booking->pickup_datetime->format('M d, Y g:i A') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Return</span>
            <span class="font-semibold text-gray-900">{{ $booking->return_datetime->format('M d, Y g:i A') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Duration</span>
            <span class="font-semibold text-gray-900">{{ $booking->total_days }} day(s)</span>
        </div>
    </div>

    <div class="space-y-2 text-sm">
        <div class="flex justify-between text-gray-600">
            <span>Base rate ({{ $booking->total_days }}d &times; ₱{{ number_format((float) $booking->daily_rate_snapshot, 0) }})</span>
            <span class="font-semibold text-gray-900">₱{{ number_format((float) $booking->base_amount, 2) }}</span>
        </div>
        @if((float) $booking->addons_amount > 0)
            <div class="flex justify-between text-gray-600">
                <span>Add-ons</span>
                <span class="font-semibold text-gray-900">₱{{ number_format((float) $booking->addons_amount, 2) }}</span>
            </div>
        @endif
        @if((float) $booking->processing_fee > 0)
            <div class="flex justify-between text-gray-600">
                <span>Credit Card Fee</span>
                <span class="font-semibold text-gray-900">₱{{ number_format((float) $booking->processing_fee, 2) }}</span>
            </div>
        @endif
        <div class="pt-2 border-t border-gray-200 flex justify-between font-black text-gray-950 text-base">
            <span>Total</span>
            <span>₱{{ number_format((float) $booking->total_amount, 2) }}</span>
        </div>
        <div class="bg-[#F8F9FA] rounded-xl p-3 border border-gray-200 space-y-1 text-xs mt-2">
            <div class="flex justify-between font-bold text-red-600">
                <span>Due Now ({{ $booking->payment_option === 'full' ? 'Full Payment' : 'Down Payment' }})</span>
                <span>₱{{ number_format($booking->amount_due_now, 2) }}</span>
            </div>
            @if($booking->remaining_balance > 0)
                <div class="flex justify-between text-gray-600">
                    <span>Remaining Balance (on pickup)</span>
                    <span class="font-semibold text-gray-900">₱{{ number_format((float) $booking->remaining_balance, 2) }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
