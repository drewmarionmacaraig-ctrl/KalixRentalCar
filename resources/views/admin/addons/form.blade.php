@extends('layouts.admin')

@section('header', $addon->exists ? 'Edit add-on' : 'Add add-on')

@section('content')
    <form action="{{ $addon->exists ? route('admin.addons.update', $addon) : route('admin.addons.store') }}" method="POST"
          class="max-w-2xl bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        @csrf
        @if($addon->exists) @method('PUT') @endif

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Add-on Name *</label>
            <input type="text" name="name" value="{{ old('name', $addon->name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
            <input type="text" name="description" value="{{ old('description', $addon->description) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pricing Type *</label>
                <select name="pricing_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <option value="per_day" @selected(old('pricing_type', $addon->pricing_type) === 'per_day')>Per Day</option>
                    <option value="flat" @selected(old('pricing_type', $addon->pricing_type) === 'flat')>Flat / One-time</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Price (₱) *</label>
                <input type="number" name="price" value="{{ old('price', $addon->price) }}" step="50" min="0" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
            </div>
        </div>

        <label class="flex items-center gap-2 text-xs font-semibold text-gray-700 pt-1">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $addon->is_active)) class="rounded border-gray-300 text-red-600 focus:ring-red-600">
            Active (available to customers during checkout)
        </label>

        <div class="flex items-center gap-3 pt-3">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold cursor-pointer">
                Save add-on
            </button>
            <a href="{{ route('admin.addons.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-semibold">Cancel</a>
        </div>
    </form>
@endsection
