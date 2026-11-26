<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function create(Request $request): View
    {
        $vehicles = Vehicle::query()->orderBy('name')->get();

        return view('inquiry', [
            'vehicles' => $vehicles,
            'selectedVehicleId' => $request->input('vehicle_id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'contact_no' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:2000'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
        ]);

        Inquiry::create($validated);

        return redirect()
            ->route('inquiry.create')
            ->with('success', 'Your inquiry has been sent! Our team at Foton Tagburos will reach out shortly via SMS, Viber, or WhatsApp.');
    }
}
