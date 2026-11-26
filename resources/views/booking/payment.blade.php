@extends('layouts.app')

@section('title', 'Payment — ' . $booking->booking_code)

@section('content')
    <x-booking-stepper :currentStep="3" />

    <div class="bg-[#F8F9FA] py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-950">Pay your {{ $booking->payment_option === 'full' ? 'full rental' : 'down payment' }}</h1>
                <p class="text-sm text-gray-600 mt-1">Send <strong>₱{{ number_format($booking->amount_due_now, 2) }}</strong> and upload your payment proof below.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-8">
                    <form action="{{ route('booking.payment.submit', $booking) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 space-y-6">
                        @csrf

                        @if($errors->any())
                            <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Payment Instructions & QR Holder Box --}}
                        <div class="bg-[#F8F9FA] rounded-xl border border-gray-200 p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                            <div class="space-y-1.5">
                                <span class="text-xs font-bold uppercase tracking-wider text-red-600">
                                    {{ $method?->name ?? strtoupper(str_replace('_', ' ', $booking->payment_method)) }}
                                </span>
                                @if($booking->payment_method === 'credit_card')
                                    <div class="text-sm font-bold text-gray-950">Credit Card Payment (includes {{ number_format((float) $settings->credit_card_fee_percentage, 0) }}% processing fee)</div>
                                    <div class="text-xs text-gray-600">Please upload your payment confirmation screenshot or POS receipt.</div>
                                @elseif($method)
                                    @if($method->account_name)
                                        <div class="text-sm font-bold text-gray-950">{{ $method->account_name }}</div>
                                    @endif
                                    @if($method->account_number)
                                        <div class="text-lg font-black text-gray-950">{{ $method->account_number }}</div>
                                    @endif
                                    @if($method->instructions)
                                        <div class="text-xs text-gray-600">{{ $method->instructions }}</div>
                                    @endif
                                @endif
                                <div class="text-xs text-gray-500 pt-1">Amount to send: <strong class="text-red-600 text-sm">₱{{ number_format($booking->amount_due_now, 2) }}</strong></div>
                            </div>

                            {{-- QR Code Image Holder --}}
                            <div class="w-36 h-36 rounded-xl border border-gray-200 bg-white p-2 flex flex-col items-center justify-center shrink-0">
                                @if($method?->qr_url)
                                    <img src="{{ $method->qr_url }}" alt="{{ $method->name }} QR Code" class="w-full h-full object-contain rounded-lg">
                                @else
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 14.625h2.25v2.25H13.5v-2.25zm4.5 0h2.25v2.25H18v-2.25zm-4.5 4.5h2.25v2.25H13.5v-2.25zm4.5-2.25h2.25v4.5H18v-4.5z"/>
                                    </svg>
                                    <span class="text-[10px] font-semibold text-gray-400 text-center mt-1.5">QR Code</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Reference / Transaction Number *</label>
                            <input type="text" name="reference_number" value="{{ old('reference_number') }}" required placeholder="e.g. 9012 3456 7890" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                        </div>

                        <div x-data="{ proofPreview: '', proofName: '' }">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Upload Payment Proof (Screenshot / Receipt) *</label>
                            <div x-show="proofPreview" class="mb-3 rounded-xl border border-gray-200 bg-[#F8F9FA] p-2">
                                <img :src="proofPreview" alt="Payment proof preview" class="max-h-60 mx-auto rounded-lg object-contain">
                            </div>
                            <label class="flex flex-col items-center justify-center gap-2 w-full rounded-xl border-2 border-dashed border-gray-300 hover:border-red-500 bg-[#F8F9FA] hover:bg-red-50/30 px-4 py-5 cursor-pointer transition">
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold shadow-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span x-text="proofPreview ? 'Change Payment Proof' : 'Upload Payment Proof'"></span>
                                </span>
                                <span class="text-xs text-gray-500 truncate max-w-full" x-text="proofName || 'Accepted formats: JPG, PNG, WEBP up to 5MB'"></span>
                                <input type="file" name="proof_image" accept="image/*" required @change="if($event.target.files[0]) { proofName = $event.target.files[0].name; proofPreview = URL.createObjectURL($event.target.files[0]); }" class="sr-only">
                            </label>
                        </div>

                        <button type="submit" class="w-full py-3.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition cursor-pointer">
                            Submit payment proof &rarr;
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-4 lg:sticky lg:top-24">
                    @include('booking.partials.summary', ['booking' => $booking])
                </div>
            </div>
        </div>
    </div>
@endsection
