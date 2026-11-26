<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingPayment;
use App\Models\PaymentMethod;
use App\Models\PaymentSetting;
use App\Models\Vehicle;
use App\Services\AvailabilityService;
use App\Services\ContractPdfService;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function checkout(Request $request, Vehicle $vehicle, PricingService $pricingService): View
    {
        $addons = Addon::active()->orderBy('name')->get();
        $settings = PaymentSetting::current();
        $paymentMethods = PaymentMethod::enabled()->orderBy('sort_order')->orderBy('id')->get();

        $pickup = $request->filled('pickup_datetime')
            ? Carbon::parse($request->input('pickup_datetime'))
            : now()->addDay()->setTime(9, 0);
        $return = $request->filled('return_datetime')
            ? Carbon::parse($request->input('return_datetime'))
            : now()->addDays(3)->setTime(9, 0);

        if ($return->lte($pickup)) {
            $return = $pickup->copy()->addDay();
        }

        $selectedAddonIds = collect((array) $request->input('addons', []))
            ->filter()
            ->map(fn ($id) => (int) $id);
        $selectedAddons = $addons->whereIn('id', $selectedAddonIds);

        $defaultMethod = $paymentMethods->first()?->code ?? 'credit_card';

        $breakdown = $pricingService->calculateBreakdown(
            $vehicle,
            $pickup,
            $return,
            $selectedAddons,
            'down_payment',
            $defaultMethod,
            $settings
        );

        return view('booking.checkout', [
            'vehicle' => $vehicle,
            'addons' => $addons,
            'settings' => $settings,
            'paymentMethods' => $paymentMethods,
            'pickup' => $pickup,
            'return' => $return,
            'selectedAddonIds' => $selectedAddonIds->all(),
            'breakdown' => $breakdown,
        ]);
    }

    public function storeCheckout(
        Request $request,
        Vehicle $vehicle,
        AvailabilityService $availabilityService,
        PricingService $pricingService
    ): RedirectResponse {
        $validated = $request->validate([
            'renter_name' => ['required', 'string', 'max:120'],
            'contact_no' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'drivers_license_no' => ['required', 'string', 'max:60'],
            'drivers_license_image' => ['required', 'image', 'max:5120'],
            'government_id_no' => ['required', 'string', 'max:60'],
            'government_id_image' => ['required', 'image', 'max:5120'],
            'primary_driver_name' => ['nullable', 'string', 'max:120'],
            'primary_driver_license' => ['nullable', 'string', 'max:60'],
            'additional_driver_name' => ['nullable', 'string', 'max:120'],
            'additional_driver_license' => ['nullable', 'string', 'max:60'],
            'pickup_datetime' => ['required', 'date', 'after_or_equal:today'],
            'return_datetime' => ['required', 'date', 'after:pickup_datetime'],
            'pickup_location' => ['required', 'string', 'max:160'],
            'return_location' => ['required', 'string', 'max:160'],
            'route_intended_use' => ['nullable', 'string', 'max:200'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer', 'exists:addons,id'],
            'payment_option' => ['required', 'in:down_payment,full'],
            'payment_method' => ['required', 'string', 'max:80'],
        ]);

        $pickup = Carbon::parse($validated['pickup_datetime']);
        $return = Carbon::parse($validated['return_datetime']);

        $selectedAddons = ! empty($validated['addons'])
            ? Addon::whereIn('id', $validated['addons'])->where('is_active', true)->get()
            : collect();

        $settings = PaymentSetting::current();
        $breakdown = $pricingService->calculateBreakdown(
            $vehicle,
            $pickup,
            $return,
            $selectedAddons,
            $validated['payment_option'],
            $validated['payment_method'],
            $settings
        );

        $licenseImagePath = $request->file('drivers_license_image')->store('renter-ids', 'public');
        $govIdImagePath = $request->file('government_id_image')->store('renter-ids', 'public');

        $booking = DB::transaction(function () use ($validated, $vehicle, $pickup, $return, $breakdown, $availabilityService, $licenseImagePath, $govIdImagePath) {
            $lockedVehicle = Vehicle::where('id', $vehicle->id)->lockForUpdate()->firstOrFail();

            if (! $availabilityService->isVehicleAvailable($lockedVehicle, $pickup, $return)) {
                return null;
            }

            $booking = Booking::create([
                'booking_code' => Booking::generateCode(),
                'vehicle_id' => $lockedVehicle->id,
                'renter_name' => $validated['renter_name'],
                'contact_no' => $validated['contact_no'],
                'email' => $validated['email'] ?? null,
                'drivers_license_no' => $validated['drivers_license_no'],
                'drivers_license_image_path' => $licenseImagePath,
                'government_id_no' => $validated['government_id_no'],
                'government_id_image_path' => $govIdImagePath,
                'primary_driver_name' => ! empty($validated['primary_driver_name']) ? $validated['primary_driver_name'] : $validated['renter_name'],
                'primary_driver_license' => ! empty($validated['primary_driver_license']) ? $validated['primary_driver_license'] : $validated['drivers_license_no'],
                'additional_driver_name' => $validated['additional_driver_name'] ?? null,
                'additional_driver_license' => $validated['additional_driver_license'] ?? null,
                'pickup_datetime' => $pickup,
                'return_datetime' => $return,
                'pickup_location' => $validated['pickup_location'],
                'return_location' => $validated['return_location'],
                'route_intended_use' => $validated['route_intended_use'] ?? null,
                'daily_rate_snapshot' => $breakdown['daily_rate'],
                'total_days' => $breakdown['days'],
                'base_amount' => $breakdown['base_amount'],
                'addons_amount' => $breakdown['addons_amount'],
                'processing_fee' => $breakdown['processing_fee'],
                'total_amount' => $breakdown['total_amount'],
                'down_payment_amount' => $breakdown['down_payment_amount'],
                'remaining_balance' => $breakdown['remaining_balance'],
                'payment_option' => $validated['payment_option'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending_payment',
            ]);

            foreach ($breakdown['addon_lines'] as $line) {
                BookingAddon::create([
                    'booking_id' => $booking->id,
                    'addon_id' => $line['addon']->id,
                    'name_snapshot' => $line['name_snapshot'],
                    'pricing_type_snapshot' => $line['pricing_type_snapshot'],
                    'unit_price_snapshot' => $line['unit_price_snapshot'],
                    'days' => $line['days'],
                    'subtotal' => $line['subtotal'],
                ]);
            }

            return $booking;
        });

        if (! $booking) {
            return back()
                ->withInput()
                ->withErrors(['pickup_datetime' => 'This vehicle is already booked or unavailable for the selected dates. Please choose different dates or another vehicle.']);
        }

        return redirect()->route('booking.contract', $booking);
    }

    public function contract(Booking $booking): View
    {
        $booking->load(['vehicle', 'addons']);

        return view('booking.contract', [
            'booking' => $booking,
        ]);
    }

    public function acceptContract(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'agree_terms' => ['accepted'],
            'contract_signature_name' => ['required', 'string', 'min:2', 'max:120'],
        ], [
            'agree_terms.accepted' => 'You must read and accept the Car Rental Agreement before proceeding to payment.',
            'contract_signature_name.required' => 'Please type your full name as your digital signature.',
        ]);

        $booking->update([
            'contract_accepted_at' => now(),
            'contract_signature_name' => $validated['contract_signature_name'],
        ]);

        return redirect()->route('booking.payment', $booking);
    }

    public function payment(Booking $booking): View|RedirectResponse
    {
        if (! $booking->contract_accepted_at) {
            return redirect()->route('booking.contract', $booking)
                ->withErrors(['agree_terms' => 'Please review and accept the rental agreement first.']);
        }

        $booking->load(['vehicle', 'addons']);
        $settings = PaymentSetting::current();
        $method = PaymentMethod::where('code', $booking->payment_method)->first();

        return view('booking.payment', [
            'booking' => $booking,
            'settings' => $settings,
            'method' => $method,
        ]);
    }

    public function submitPayment(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'reference_number' => ['required', 'string', 'max:100'],
            'proof_image' => ['required', 'image', 'max:5120'],
        ]);

        $path = $request->file('proof_image')->store('payment-proofs', 'public');

        BookingPayment::create([
            'booking_id' => $booking->id,
            'payment_type' => $booking->payment_option === 'full' ? 'full' : 'down_payment',
            'payment_method' => $booking->payment_method,
            'amount' => $booking->amount_due_now,
            'reference_number' => $validated['reference_number'],
            'proof_image_path' => $path,
            'status' => 'pending',
        ]);

        $booking->update([
            'status' => 'payment_submitted',
        ]);

        return redirect()->route('booking.confirmation', $booking);
    }

    public function confirmation(Booking $booking): View
    {
        $booking->load(['vehicle', 'addons', 'payments']);

        return view('booking.confirmation', [
            'booking' => $booking,
        ]);
    }

    public function downloadContract(Booking $booking, ContractPdfService $pdfService): Response
    {
        return $pdfService->download($booking);
    }
}
