@extends('layouts.admin')

@section('header', $vehicle->exists ? 'Edit vehicle' : 'Add vehicle')

@section('content')
    <form action="{{ $vehicle->exists ? route('admin.vehicles.update', $vehicle) : route('admin.vehicles.store') }}"
          method="POST" enctype="multipart/form-data"
          class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        @csrf
        @if($vehicle->exists) @method('PUT') @endif

        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-bold text-gray-950 text-sm">Vehicle details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Display Name *</label>
                    <input type="text" name="name" value="{{ old('name', $vehicle->name) }}" required placeholder="e.g. Toyota Innova 2.8 E" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Brand *</label>
                    <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Model *</label>
                    <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Plate / Conduction No. *</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Year *</label>
                    <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Category *</label>
                    <select name="category" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        @foreach(['Sedan', 'MPV', 'SUV', 'Van', 'Pickup', 'Hatchback'] as $cat)
                            <option value="{{ $cat }}" @selected(old('category', $vehicle->category) === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Transmission *</label>
                    <select name="transmission" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        <option value="Automatic" @selected(old('transmission', $vehicle->transmission) === 'Automatic')>Automatic</option>
                        <option value="Manual" @selected(old('transmission', $vehicle->transmission) === 'Manual')>Manual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Seats *</label>
                    <input type="number" name="seats" value="{{ old('seats', $vehicle->seats) }}" required min="2" max="30" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Fuel Type *</label>
                    <select name="fuel_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        @foreach(['Gasoline', 'Diesel', 'Hybrid', 'Electric'] as $fuel)
                            <option value="{{ $fuel }}" @selected(old('fuel_type', $vehicle->fuel_type) === $fuel)>{{ $fuel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Daily Rate (₱) *</label>
                    <input type="number" name="daily_rate" value="{{ old('daily_rate', $vehicle->daily_rate) }}" required min="100" step="50" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">
                        <option value="available" @selected(old('status', $vehicle->status) === 'available')>Available</option>
                        <option value="rented" @selected(old('status', $vehicle->status) === 'rented')>Rented</option>
                        <option value="maintenance" @selected(old('status', $vehicle->status) === 'maintenance')>Maintenance</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600">{{ old('description', $vehicle->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div x-data="{
                    primaryPreview: '{{ $vehicle->exists ? $vehicle->image_url : '' }}',
                    primaryName: '',
                    galleryPreviews: [],
                    handlePrimary(e) {
                        const file = e.target.files[0];
                        if (!file) return;
                        this.primaryName = file.name;
                        this.primaryPreview = URL.createObjectURL(file);
                    },
                    handleGallery(e) {
                        this.galleryPreviews = Array.from(e.target.files).map(f => ({
                            name: f.name,
                            url: URL.createObjectURL(f)
                        }));
                    }
                 }"
                 class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-gray-950 text-sm">Photos</h2>
                    @if($vehicle->exists)
                        <span class="text-[11px] font-medium text-gray-500">Current vehicle image</span>
                    @endif
                </div>

                {{-- Current / Live Preview Image at the Top --}}
                <div class="relative aspect-[16/10] w-full rounded-xl border border-gray-200 bg-[#F8F9FA] overflow-hidden flex items-center justify-center">
                    <template x-if="primaryPreview">
                        <img :src="primaryPreview" alt="Vehicle preview" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!primaryPreview">
                        <div class="text-center px-4 py-6 space-y-1.5">
                            <svg class="w-8 h-8 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <p class="text-xs font-semibold text-gray-500">No vehicle photo uploaded yet</p>
                        </div>
                    </template>
                </div>

                {{-- Styled Primary Photo Upload Button --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Primary Photo</label>
                    <label class="flex flex-col items-center justify-center gap-2 w-full rounded-xl border-2 border-dashed border-gray-300 hover:border-red-500 bg-[#F8F9FA] hover:bg-red-50/30 px-4 py-4 cursor-pointer transition">
                        <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span x-text="primaryPreview ? 'Change Primary Photo' : 'Upload Primary Photo'"></span>
                        </span>
                        <span class="text-[11px] text-gray-500 truncate max-w-full" x-text="primaryName || 'JPG, PNG, WEBP up to 5MB'"></span>
                        <input type="file" name="primary_image" accept="image/*" @change="handlePrimary($event)" class="sr-only">
                    </label>
                </div>

                {{-- Styled Gallery Photos Upload Button --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Additional Gallery Photos</label>

                    @if($vehicle->exists && $vehicle->images->isNotEmpty())
                        <div class="grid grid-cols-4 gap-2 mb-2.5">
                            @foreach($vehicle->images as $img)
                                <img src="{{ $img->url }}" alt="" class="aspect-[16/10] w-full rounded-lg object-cover border border-gray-200">
                            @endforeach
                        </div>
                    @endif

                    <div x-show="galleryPreviews.length > 0" class="grid grid-cols-4 gap-2 mb-2.5">
                        <template x-for="(item, idx) in galleryPreviews" :key="idx">
                            <img :src="item.url" :alt="item.name" class="aspect-[16/10] w-full rounded-lg object-cover border-2 border-red-500">
                        </template>
                    </div>

                    <label class="flex items-center justify-between gap-3 w-full rounded-xl border border-gray-300 hover:border-gray-400 bg-white px-3.5 py-2.5 cursor-pointer transition">
                        <span class="text-xs text-gray-600 truncate" x-text="galleryPreviews.length ? galleryPreviews.length + ' file(s) selected' : 'Select multiple photos...'"></span>
                        <span class="px-3 py-1.5 rounded-lg bg-gray-950 hover:bg-gray-800 text-white text-xs font-semibold shrink-0 transition">
                            Choose Files
                        </span>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple @change="handleGallery($event)" class="sr-only">
                    </label>
                </div>

                <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 pt-2 border-t border-gray-100">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $vehicle->is_featured)) class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                    Feature on Home Page ("Ready-to-go rides")
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition cursor-pointer">
                    Save vehicle
                </button>
                <a href="{{ route('admin.vehicles.index') }}" class="px-4 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 text-sm font-semibold">
                    Cancel
                </a>
            </div>
        </div>
    </form>
@endsection
