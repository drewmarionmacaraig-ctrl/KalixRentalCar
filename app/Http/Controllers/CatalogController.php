<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\PaymentSetting;
use App\Models\Vehicle;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request, AvailabilityService $availabilityService): View
    {
        $query = Vehicle::query()->orderBy('daily_rate');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('transmission')) {
            $query->where('transmission', $request->string('transmission')->toString());
        }

        if ($request->filled('seats')) {
            $query->where('seats', '>=', (int) $request->input('seats'));
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->string('fuel_type')->toString());
        }

        if ($request->filled('max_price')) {
            $query->where('daily_rate', '<=', (float) $request->input('max_price'));
        }

        $vehicles = $query->get();

        $pickup = $request->filled('pickup_datetime') ? Carbon::parse($request->input('pickup_datetime')) : null;
        $return = $request->filled('return_datetime') ? Carbon::parse($request->input('return_datetime')) : null;

        if ($pickup && $return && $return->gt($pickup)) {
            $vehicles = $vehicles->map(function (Vehicle $vehicle) use ($availabilityService, $pickup, $return) {
                $vehicle->setAttribute('is_available_for_dates', $availabilityService->isVehicleAvailable($vehicle, $pickup, $return));
                return $vehicle;
            });

            if ($request->boolean('available_only')) {
                $vehicles = $vehicles->filter(fn (Vehicle $v) => $v->getAttribute('is_available_for_dates'));
            }
        }

        $categories = Vehicle::query()->distinct()->pluck('category');

        return view('catalog.index', [
            'vehicles' => $vehicles,
            'categories' => $categories,
            'pickup' => $pickup,
            'return' => $return,
        ]);
    }

    public function show(Request $request, Vehicle $vehicle): View
    {
        $vehicle->load('images');
        $addons = Addon::active()->orderBy('name')->get();
        $paymentSettings = PaymentSetting::current();

        return view('catalog.show', [
            'vehicle' => $vehicle,
            'addons' => $addons,
            'paymentSettings' => $paymentSettings,
            'prefillPickup' => $request->input('pickup_datetime', now()->addDay()->setTime(9, 0)->format('Y-m-d\TH:i')),
            'prefillReturn' => $request->input('return_datetime', now()->addDays(3)->setTime(9, 0)->format('Y-m-d\TH:i')),
        ]);
    }
}
