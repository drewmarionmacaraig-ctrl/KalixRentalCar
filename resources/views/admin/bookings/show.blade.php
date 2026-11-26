@extends('layouts.admin')

@section('header', 'Booking ' . $booking->booking_code)

@section('actions')
    <a href="{{ route('booking.contract.pdf', $booking) }}" target="_blank" class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-800 text-xs font-semibold transition">
        Download Contract PDF
    </a>
    @if(in_array($booking->status, ['confirmed', 'rented']))
        <a href="{{ route('admin.bookings.extend', $booking) }}" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
            Extend rental
        </a>
    @endif
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Left Column: Renter, Vehicle, Price Breakdown, Contract --}}
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-950 text-base">Renter &amp; trip details</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Created {{ $booking->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    <x-status-badge :status="$booking->status" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-2 border-t border-gray-100">
                    <div><span class="text-gray-400 block">Renter Name</span><strong class="text-gray-950 text-sm">{{ $booking->renter_name }}</strong></div>
                    <div><span class="text-gray-400 block">Contact No.</span><strong class="text-gray-950 text-sm">{{ $booking->contact_no }}</strong></div>
                    <div><span class="text-gray-400 block">Driver's License</span><strong class="text-gray-950">{{ $booking->drivers_license_no }}</strong></div>
                    <div><span class="text-gray-400 block">Government ID</span><strong class="text-gray-950">{{ $booking->government_id_no }}</strong></div>
                    <div><span class="text-gray-400 block">Vehicle</span><strong class="text-gray-950">{{ $booking->vehicle->name }} ({{ $booking->vehicle->plate_number }})</strong></div>
                    <div><span class="text-gray-400 block">Route / Intended Use</span><strong class="text-gray-950">{{ $booking->route_intended_use ?? 'Palawan' }}</strong></div>
                    <div><span class="text-gray-400 block">Pickup</span><strong class="text-gray-950">{{ $booking->pickup_datetime->format('M d, Y g:i A') }}</strong><div class="text-gray-500">{{ $booking->pickup_location }}</div></div>
                    <div><span class="text-gray-400 block">Return</span><strong class="text-gray-950">{{ $booking->return_datetime->format('M d, Y g:i A') }}</strong><div class="text-gray-500">{{ $booking->return_location }}</div></div>
                </div>

                {{-- Renter ID Photos --}}
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-3">Uploaded IDs for Verification</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-xl border border-gray-200 bg-[#F8F9FA] p-3 space-y-2">
                            <div class="text-xs font-semibold text-gray-700">Driver's License ({{ $booking->drivers_license_no }})</div>
                            @if($booking->drivers_license_image_url)
                                <a href="{{ $booking->drivers_license_image_url }}" target="_blank" class="block">
                                    <img src="{{ $booking->drivers_license_image_url }}" alt="Driver's License" class="w-full h-40 object-cover rounded-lg border border-gray-200 bg-white">
                                </a>
                            @else
                                <div class="h-40 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-xs text-gray-400">No image uploaded</div>
                            @endif
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-[#F8F9FA] p-3 space-y-2">
                            <div class="text-xs font-semibold text-gray-700">Government ID ({{ $booking->government_id_no }})</div>
                            @if($booking->government_id_image_url)
                                <a href="{{ $booking->government_id_image_url }}" target="_blank" class="block">
                                    <img src="{{ $booking->government_id_image_url }}" alt="Government ID" class="w-full h-40 object-cover rounded-lg border border-gray-200 bg-white">
                                </a>
                            @else
                                <div class="h-40 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-xs text-gray-400">No image uploaded</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Price Breakdown --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-3 text-sm">
                <h2 class="font-bold text-gray-950 text-base">Price breakdown</h2>
                <div class="flex justify-between text-gray-600">
                    <span>Base rate ({{ $booking->total_days }} days &times; ₱{{ number_format((float) $booking->daily_rate_snapshot, 0) }})</span>
                    <span class="font-semibold text-gray-900">₱{{ number_format((float) $booking->base_amount, 2) }}</span>
                </div>
                @foreach($booking->addons as $addon)
                    <div class="flex justify-between text-gray-600 text-xs">
                        <span>Add-on: {{ $addon->name_snapshot }}</span>
                        <span>₱{{ number_format((float) $addon->subtotal, 2) }}</span>
                    </div>
                @endforeach
                @if((float) $booking->processing_fee > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Credit Card Processing Fee</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format((float) $booking->processing_fee, 2) }}</span>
                    </div>
                @endif
                <div class="pt-3 border-t border-gray-200 flex justify-between font-black text-gray-950 text-base">
                    <span>Total Amount</span>
                    <span>₱{{ number_format((float) $booking->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-600">
                    <span>Down Payment / Paid Upfront</span>
                    <span class="font-semibold">₱{{ number_format((float) $booking->down_payment_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs text-red-600 font-bold">
                    <span>Remaining Balance</span>
                    <span>₱{{ number_format((float) $booking->remaining_balance, 2) }}</span>
                </div>
            </div>

            {{-- Release / Return Checklist Forms --}}
            @if($booking->status === 'confirmed')
                <form action="{{ route('admin.bookings.rented', $booking) }}" method="POST" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                    @csrf
                    <h2 class="font-bold text-gray-950 text-base">Vehicle Release Checklist &amp; Handover</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @foreach(['exterior' => 'Exterior / Body', 'interior' => 'Interior', 'tires' => 'Tires / Wheels', 'lights' => 'Lights / Signals', 'fuel' => 'Fuel Level', 'accessories' => 'Accessories / Documents'] as $key => $label)
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">{{ $label }}</label>
                                <input type="text" name="checklist[{{ $key }}]" value="Good / Complete" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-xs">
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition cursor-pointer">
                        Mark as Rented (Release Vehicle)
                    </button>
                </form>
            @elseif($booking->status === 'rented')
                <form action="{{ route('admin.bookings.completed', $booking) }}" method="POST" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                    @csrf
                    <h2 class="font-bold text-gray-950 text-base">Vehicle Return Checklist &amp; Completion</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @foreach(['exterior' => 'Exterior / Body', 'interior' => 'Interior', 'tires' => 'Tires / Wheels', 'lights' => 'Lights / Signals', 'fuel' => 'Fuel Level', 'accessories' => 'Accessories / Documents'] as $key => $label)
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">{{ $label }}</label>
                                <input type="text" name="checklist[{{ $key }}]" value="Good / Returned" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-xs">
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition cursor-pointer">
                        Complete Rental (Return Vehicle)
                    </button>
                </form>
            @endif
        </div>

        {{-- Right Column: Payment Proof Review & Actions --}}
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                <h2 class="font-bold text-gray-950 text-base">Payment proofs</h2>

                @forelse($booking->payments as $payment)
                    <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold uppercase text-gray-700">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                            <x-status-badge :status="$payment->status" />
                        </div>
                        <div class="text-xs text-gray-600 space-y-1">
                            <div>Amount: <strong class="text-gray-950">₱{{ number_format((float) $payment->amount, 2) }}</strong></div>
                            <div>Reference No.: <strong class="text-gray-950">{{ $payment->reference_number ?? 'N/A' }}</strong></div>
                        </div>
                        @if($payment->proof_url)
                            <a href="{{ $payment->proof_url }}" target="_blank" class="block">
                                <img src="{{ $payment->proof_url }}" alt="Payment proof" class="w-full max-h-64 object-contain rounded-lg border border-gray-200 bg-gray-50">
                            </a>
                        @endif

                        @if($payment->status === 'pending')
                            <div class="flex items-center gap-2 pt-2">
                                <form action="{{ route('admin.bookings.payments.approve', [$booking, $payment]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold cursor-pointer">
                                        Approve &amp; Confirm
                                    </button>
                                </form>
                            </div>
                            <form action="{{ route('admin.bookings.payments.reject', [$booking, $payment]) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="text" name="rejection_reason" required placeholder="Reason for rejection..." class="flex-1 rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold cursor-pointer">
                                    Reject
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No payment proofs uploaded yet.</p>
                @endforelse
            </div>

            @if(!in_array($booking->status, ['completed', 'cancelled']))
                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Cancel this booking?')" class="bg-white rounded-2xl border border-gray-200 p-5">
                    @csrf
                    <button type="submit" class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 text-xs font-semibold transition cursor-pointer">
                        Cancel Booking
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
