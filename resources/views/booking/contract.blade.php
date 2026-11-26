@extends('layouts.app')

@section('title', 'Rental Agreement — ' . $booking->booking_code)

@section('content')
    <x-booking-stepper :currentStep="2" />

    <div class="bg-[#F8F9FA] py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-black text-gray-950">Review and accept the rental agreement</h1>
                <p class="text-sm text-gray-600 mt-1">Please read the KLX Car Rental Agreement carefully and sign below to proceed.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-8 space-y-6">
                    {{-- Scrollable Rental Agreement --}}
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 space-y-5 max-h-[540px] overflow-y-auto text-xs sm:text-sm text-gray-700 leading-relaxed">
                        <div class="text-center border-b border-gray-200 pb-4">
                            <div class="text-red-700 font-black text-base tracking-wide">KLX AFFORDABLE CARS FOR RENT – PALAWAN</div>
                            <div class="text-xs text-red-600 mt-0.5">Drive More, Explore More | Foton Tagburos, Puerto Princesa | SMS / Viber / WhatsApp: 0907-883-8861</div>
                            <div class="font-bold text-gray-950 text-sm mt-2">CAR RENTAL AGREEMENT</div>
                        </div>

                        <p>This agreement is made between <strong>KLX Affordable Cars for Rent – Palawan ("Lessor")</strong> and <strong>{{ $booking->renter_name }} ("Renter")</strong>.</p>

                        <div>
                            <h3 class="font-bold text-red-700 mb-2">1. RENTER &amp; VEHICLE DETAILS</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-[#F8F9FA] p-3 rounded-lg border border-gray-200 text-xs">
                                <div><span class="text-gray-500">Renter Name:</span> <strong>{{ $booking->renter_name }}</strong></div>
                                <div><span class="text-gray-500">Contact No.:</span> <strong>{{ $booking->contact_no }}</strong></div>
                                <div><span class="text-gray-500">Driver's License No.:</span> <strong>{{ $booking->drivers_license_no }}</strong></div>
                                <div><span class="text-gray-500">Government ID No.:</span> <strong>{{ $booking->government_id_no }}</strong></div>
                                <div><span class="text-gray-500">Vehicle / Unit:</span> <strong>{{ $booking->vehicle->name }}</strong></div>
                                <div><span class="text-gray-500">Plate / Conduction No.:</span> <strong>{{ $booking->vehicle->plate_number }}</strong></div>
                                <div><span class="text-gray-500">Rental Start:</span> <strong>{{ $booking->pickup_datetime->format('M d, Y g:i A') }}</strong></div>
                                <div><span class="text-gray-500">Return Date:</span> <strong>{{ $booking->return_datetime->format('M d, Y g:i A') }}</strong></div>
                                <div><span class="text-gray-500">Pickup Location:</span> <strong>{{ $booking->pickup_location }}</strong></div>
                                <div><span class="text-gray-500">Return Location:</span> <strong>{{ $booking->return_location }}</strong></div>
                                <div><span class="text-gray-500">Route / Intended Use:</span> <strong>{{ $booking->route_intended_use ?? 'Palawan' }}</strong></div>
                                <div><span class="text-gray-500">Total Rental Rate:</span> <strong>₱{{ number_format((float) $booking->total_amount, 2) }}</strong></div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">2. PAYMENT &amp; RESERVATION</h3>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Reservation is confirmed only after the agreed down payment is received and acknowledged by KLX. The down payment is <strong>NON-REFUNDABLE</strong>, subject to agreed terms and applicable law.</li>
                                <li>Remaining balance must be paid on or before the agreed rental schedule. Accepted methods may include GCash, PalawanPay, bank transfer, and credit card; credit card transactions carry a 6% bank/processing charge.</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">3. VEHICLE CONDITION &amp; DELIVERY</h3>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Renter must inspect the vehicle upon release and document existing scratches, dents, damage, fuel level, mileage, and accessories before use. Return the vehicle in substantially the same condition, subject to normal wear and tear.</li>
                                <li>Delivery/pickup fees are separate from the rental rate unless expressly included in the booking.</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">4. RENTER RESPONSIBILITIES</h3>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Use the vehicle with reasonable care, lawfully, and within the agreed route/use. Do not sub-rent, lend, sell, alter, or allow an unauthorized person to operate it.</li>
                                <li>Renter is responsible, subject to this agreement and applicable law, for damage, loss, expense, traffic violations, parking, tolls, impound fees, and other renter-caused charges during the rental period.</li>
                                <li>Report accidents, damage, breakdowns, or other issues to KLX immediately.</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">5. DAMAGE, LOSS &amp; ACCIDENTS</h3>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Report new damage or loss immediately. Charges may be based on actual repair, replacement, recovery, or other documented cost, subject to applicable law.</li>
                                <li>In an accident, prioritize safety, notify authorities when required, and contact KLX as soon as reasonably possible. Do not admit liability, abandon the vehicle, or arrange major repairs without contacting KLX first, except when emergency action is reasonably necessary for safety.</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">6. RETURN, EXTENSION &amp; LATE RETURN</h3>
                            <p>Return the vehicle at the agreed date, time, and location. Extensions require KLX approval before the original return time and may incur additional charges subject to availability. Late return may result in charges under the agreed rental terms.</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">7. FUEL, CLEANLINESS &amp; FEES</h3>
                            <p>Return the vehicle at the agreed fuel level and cleanliness condition. Carwash, delivery/pickup, fuel, toll, parking, and other agreed charges are payable under the booking terms.</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">8. AUTHORIZED DRIVER</h3>
                            <p>Primary Driver: <strong>{{ $booking->primary_driver_name }}</strong> (License: <strong>{{ $booking->primary_driver_license }}</strong>)
                            @if($booking->additional_driver_name)
                                | Additional Driver: <strong>{{ $booking->additional_driver_name }}</strong> (License: <strong>{{ $booking->additional_driver_license }}</strong>)
                            @endif
                            </p>
                            <p class="mt-1">Only drivers disclosed to and accepted by KLX may operate the vehicle. Renter remains responsible for the vehicle and acts or omissions of any authorized driver.</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-red-700 mb-1">9. CANCELLATION / CHANGES</h3>
                            <p>Changes to dates, vehicle/unit, pickup location, or rental duration are subject to availability and KLX approval. The non-refundable down payment applies under booking terms communicated to the Renter.</p>
                        </div>

                        <div class="border-t border-red-200 pt-3 bg-red-50/50 p-3 rounded-lg">
                            <h3 class="font-bold text-red-700 mb-1">DAMAGE, REPAIR COST &amp; DAILY RENTAL CHARGE</h3>
                            <p>If damage results from the renter's negligence, misuse, reckless driving, unauthorized use, or breach of this agreement, the renter must pay the actual repair or replacement cost and reasonable related towing, parts, labor, assessment, and repair costs. The renter must also pay the agreed daily rental rate for each day the vehicle is unavailable for rent during repairs, subject to applicable law.</p>
                        </div>
                    </div>

                    {{-- Acceptance & Digital Signature Form --}}
                    <form action="{{ route('booking.contract.accept', $booking) }}" method="POST"
                        class="bg-white rounded-2xl border {{ $errors->any() ? 'border-red-500 ring-2 ring-red-100' : 'border-gray-200' }} p-6 space-y-4">
                        @csrf
                        <h2 class="text-base font-bold text-gray-950">10. Acknowledgment &amp; Digital Signature</h2>

                        @if($errors->any())
                            <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-xs text-red-700 font-medium">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Printed Name / Digital Signature *</label>
                            <input type="text" name="contract_signature_name" value="{{ old('contract_signature_name', $booking->renter_name) }}" class="w-full rounded-lg border {{ $errors->has('contract_signature_name') ? 'border-red-500' : 'border-gray-300' }} px-3 py-2.5 text-sm focus:border-red-600 focus:ring-red-600">
                        </div>

                        <label class="flex items-start gap-3 p-3 rounded-xl border {{ $errors->has('agree_terms') ? 'border-red-400 bg-red-50/40' : 'border-gray-200 bg-[#F8F9FA]' }} cursor-pointer text-xs sm:text-sm text-gray-700">
                            <input type="checkbox" name="agree_terms" value="1" @checked(old('agree_terms') || $booking->contract_accepted_at) class="mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-600">
                            <span>I confirm the information is accurate, I have read and understood the <strong>KLX Car Rental Agreement</strong> (including the Damage, Repair Cost &amp; Daily Rental Charge clause and non-refundable down payment policy), and I agree to its terms.</span>
                        </label>

                        <button type="submit" class="w-full py-3.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition cursor-pointer">
                            Accept &amp; Continue to Payment &rarr;
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
