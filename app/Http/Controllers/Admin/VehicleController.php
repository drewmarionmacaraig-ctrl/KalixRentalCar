<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vehicle::query()->orderBy('name');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $s = $request->string('search')->toString();
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('plate_number', 'like', "%{$s}%")
                    ->orWhere('brand', 'like', "%{$s}%");
            });
        }

        return view('admin.vehicles.index', [
            'vehicles' => $query->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.vehicles.form', array_merge([
            'vehicle' => new Vehicle(['status' => 'available', 'transmission' => 'Automatic', 'seats' => 5, 'year' => date('Y')]),
        ], $this->getFormOptions()));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateVehicle($request);
        $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['plate_number']);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('primary_image')) {
            $validated['primary_image_path'] = $request->file('primary_image')->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create($validated);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $idx => $file) {
                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => $file->store('vehicles', 'public'),
                    'sort_order' => $idx,
                ]);
            }
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicle $vehicle): View
    {
        $vehicle->load('images');

        return view('admin.vehicles.form', array_merge([
            'vehicle' => $vehicle,
        ], $this->getFormOptions()));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $this->validateVehicle($request, $vehicle->id);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('primary_image')) {
            $validated['primary_image_path'] = $request->file('primary_image')->store('vehicles', 'public');
        }

        $vehicle->update($validated);

        if ($request->hasFile('gallery_images')) {
            $nextOrder = ($vehicle->images()->max('sort_order') ?? 0) + 1;
            foreach ($request->file('gallery_images') as $file) {
                VehicleImage::create([
                    'vehicle_id' => $vehicle->id,
                    'image_path' => $file->store('vehicles', 'public'),
                    'sort_order' => $nextOrder++,
                ]);
            }
        }

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        if ($vehicle->bookings()->exists()) {
            return back()->withErrors([
                'vehicle' => 'Cannot delete a vehicle with existing bookings. Set its status to Maintenance instead.',
            ]);
        }

        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle removed.');
    }

    private function validateVehicle(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'brand' => ['required', 'string', 'max:60'],
            'model' => ['required', 'string', 'max:60'],
            'year' => ['required', 'integer', 'min:1995', 'max:' . (date('Y') + 2)],
            'plate_number' => ['required', 'string', 'max:30', 'unique:vehicles,plate_number,' . $ignoreId],
            'category' => ['required', 'string', 'max:40'],
            'transmission' => ['required', 'string', 'max:40'],
            'seats' => ['required', 'integer', 'min:2', 'max:30'],
            'fuel_type' => ['required', 'string', 'max:30'],
            'daily_rate' => ['required', 'numeric', 'min:100'],
            'status' => ['required', 'in:available,rented,maintenance'],
            'description' => ['nullable', 'string', 'max:2000'],
            'primary_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],
        ]);
    }

    private function getFormOptions(): array
    {
        $categories = collect(['Sedan', 'MPV', 'SUV', 'Van', 'Pickup', 'Hatchback'])
            ->merge(Vehicle::query()->distinct()->pluck('category'))
            ->filter()
            ->unique()
            ->values();

        $transmissions = collect(['Automatic', 'Manual', 'CVT'])
            ->merge(Vehicle::query()->distinct()->pluck('transmission'))
            ->filter()
            ->unique()
            ->values();

        return [
            'categories' => $categories,
            'transmissions' => $transmissions,
        ];
    }
}
