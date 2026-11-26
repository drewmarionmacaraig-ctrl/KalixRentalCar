<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingExtension;
use App\Models\BookingPayment;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with('vehicle')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $s = $request->string('search')->toString();
            $query->where(function ($q) use ($s) {
                $q->where('booking_code', 'like', "%{$s}%")
                    ->orWhere('renter_name', 'like', "%{$s}%")
                    ->orWhere('contact_no', 'like', "%{$s}%");
            });
        }

        return view('admin.bookings.index', [
            'bookings' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load(['vehicle', 'addons', 'payments', 'extensions']);

        return view('admin.bookings.show', [
            'booking' => $booking,
        ]);
    }

    public function approvePayment(Booking $booking, BookingPayment $payment): RedirectResponse
    {
        $payment->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        if (in_array($booking->status, ['pending_payment', 'payment_submitted'], true)) {
            $booking->update(['status' => 'confirmed']);
        }

        return back()->with('success', 'Payment verified and booking confirmed.');
    }

    public function rejectPayment(Request $request, Booking $booking, BookingPayment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $payment->update([
            'status' => 'rejected',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $booking->update(['status' => 'pending_payment']);

        return back()->with('success', 'Payment proof rejected.');
    }

    public function markRented(Request $request, Booking $booking): RedirectResponse
    {
        $checklist = $request->input('checklist', []);

        $booking->update([
            'status' => 'rented',
            'release_checklist' => [
                'exterior' => $checklist['exterior'] ?? 'Good',
                'interior' => $checklist['interior'] ?? 'Clean',
                'tires' => $checklist['tires'] ?? 'Good',
                'lights' => $checklist['lights'] ?? 'Working',
                'fuel' => $checklist['fuel'] ?? 'Full',
                'accessories' => $checklist['accessories'] ?? 'Complete',
            ],
        ]);

        $booking->vehicle->update(['status' => 'rented']);

        return back()->with('success', 'Vehicle marked as Rented with release checklist saved.');
    }

    public function markCompleted(Request $request, Booking $booking): RedirectResponse
    {
        $checklist = $request->input('checklist', []);

        $booking->update([
            'status' => 'completed',
            'actual_return_datetime' => now(),
            'remaining_balance' => 0,
            'return_checklist' => [
                'exterior' => $checklist['exterior'] ?? 'Good',
                'interior' => $checklist['interior'] ?? 'Clean',
                'tires' => $checklist['tires'] ?? 'Good',
                'lights' => $checklist['lights'] ?? 'Working',
                'fuel' => $checklist['fuel'] ?? 'Full',
                'accessories' => $checklist['accessories'] ?? 'Complete',
            ],
        ]);

        $booking->vehicle->update(['status' => 'available']);

        return back()->with('success', 'Rental completed and vehicle is now Available.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => 'cancelled']);
        if ($booking->vehicle->status === 'rented') {
            $booking->vehicle->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking cancelled.');
    }

    public function extendForm(
        Request $request,
        Booking $booking,
        AvailabilityService $availabilityService,
        PricingService $pricingService
    ): View {
        $booking->load('vehicle');

        $proposedReturn = $request->filled('new_return_datetime')
            ? Carbon::parse($request->input('new_return_datetime'))
            : $booking->return_datetime->copy()->addDay();

        $ratePerDay = (float) $request->input('rate_per_day', $booking->daily_rate_snapshot);
        $extraDays = $proposedReturn->gt($booking->return_datetime)
            ? $pricingService->calculateDays($booking->return_datetime, $proposedReturn)
            : 0;

        $conflict = $proposedReturn->gt($booking->return_datetime)
            ? $availabilityService->findConflictingBooking(
                $booking->vehicle,
                $booking->return_datetime,
                $proposedReturn,
                $booking->id
            )
            : null;

        return view('admin.bookings.extend', [
            'booking' => $booking,
            'proposedReturn' => $proposedReturn,
            'ratePerDay' => $ratePerDay,
            'extraDays' => $extraDays,
            'addedAmount' => round($extraDays * $ratePerDay, 2),
            'conflict' => $conflict,
        ]);
    }

    public function extendStore(
        Request $request,
        Booking $booking,
        AvailabilityService $availabilityService,
        PricingService $pricingService
    ): RedirectResponse {
        $validated = $request->validate([
            'new_return_datetime' => ['required', 'date', 'after:' . $booking->return_datetime->toDateTimeString()],
            'rate_per_day' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $newReturn = Carbon::parse($validated['new_return_datetime']);
        $conflict = $availabilityService->findConflictingBooking(
            $booking->vehicle,
            $booking->return_datetime,
            $newReturn,
            $booking->id
        );

        if ($conflict) {
            return redirect()
                ->route('admin.bookings.extend', [
                    'booking' => $booking,
                    'new_return_datetime' => $newReturn->format('Y-m-d\TH:i'),
                    'rate_per_day' => $validated['rate_per_day'],
                ])
                ->withErrors(['conflict' => "Schedule conflict with booking {$conflict->booking_code} ({$conflict->pickup_datetime->format('M d, g:i A')} – {$conflict->return_datetime->format('M d, g:i A')})."]);
        }

        $extraDays = $pricingService->calculateDays($booking->return_datetime, $newReturn);
        $ratePerDay = (float) $validated['rate_per_day'];
        $addedAmount = round($extraDays * $ratePerDay, 2);

        BookingExtension::create([
            'booking_id' => $booking->id,
            'previous_return_datetime' => $booking->return_datetime,
            'new_return_datetime' => $newReturn,
            'extra_days' => $extraDays,
            'rate_per_day' => $ratePerDay,
            'total_added_amount' => $addedAmount,
            'notes' => $validated['notes'] ?? null,
            'approved_by' => Auth::id(),
        ]);

        $booking->update([
            'return_datetime' => $newReturn,
            'total_days' => $booking->total_days + $extraDays,
            'base_amount' => (float) $booking->base_amount + $addedAmount,
            'total_amount' => (float) $booking->total_amount + $addedAmount,
            'remaining_balance' => (float) $booking->remaining_balance + $addedAmount,
        ]);

        return redirect()->route('admin.bookings.show', $booking)->with('success', "Rental extended by {$extraDays} day(s) (+₱" . number_format($addedAmount, 2) . ").");
    }
}
