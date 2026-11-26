@extends('layouts.admin')

@section('header', 'Vehicles')

@section('actions')
    <a href="{{ route('admin.vehicles.create') }}" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
        + Add vehicle
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs font-semibold">
                <a href="{{ route('admin.vehicles.index') }}" class="px-3 py-1.5 rounded-lg {{ !request('status') ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">All</a>
                <a href="{{ route('admin.vehicles.index', ['status' => 'available']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'available' ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">Available</a>
                <a href="{{ route('admin.vehicles.index', ['status' => 'rented']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'rented' ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">Rented</a>
                <a href="{{ route('admin.vehicles.index', ['status' => 'maintenance']) }}" class="px-3 py-1.5 rounded-lg {{ request('status') === 'maintenance' ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">Maintenance</a>
            </div>

            <form action="{{ route('admin.vehicles.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or plate..." class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs focus:border-red-600 focus:ring-red-600">
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-gray-950 text-white text-xs font-semibold cursor-pointer">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#F8F9FA] text-xs font-semibold text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3">Vehicle</th>
                        <th class="px-5 py-3">Plate</th>
                        <th class="px-5 py-3">Specs</th>
                        <th class="px-5 py-3">Daily Rate</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @foreach($vehicles as $v)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $v->image_url }}" alt="{{ $v->name }}" class="w-12 h-9 rounded-lg object-cover bg-gray-100">
                                    <div>
                                        <div class="font-bold text-gray-950">{{ $v->name }}</div>
                                        <div class="text-gray-500">{{ $v->category }} &bull; {{ $v->year }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 font-semibold">{{ $v->plate_number }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $v->transmission }} &bull; {{ $v->seats }} seats &bull; {{ $v->fuel_type }}</td>
                            <td class="px-5 py-3 font-bold text-gray-950">₱{{ number_format((float) $v->daily_rate, 0) }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$v->status" /></td>
                            <td class="px-5 py-3 text-right space-x-3">
                                <a href="{{ route('admin.vehicles.edit', $v) }}" class="font-semibold text-red-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.vehicles.destroy', $v) }}" method="POST" class="inline" onsubmit="return confirm('Delete this vehicle?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="font-semibold text-gray-400 hover:text-red-600 cursor-pointer">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
