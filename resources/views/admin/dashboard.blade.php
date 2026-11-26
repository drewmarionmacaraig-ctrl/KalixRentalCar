@extends('layouts.admin')

@section('header', 'Dashboard')

@section('actions')
    <a href="{{ route('admin.vehicles.create') }}" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
        + Add vehicle
    </a>
@endsection

@section('content')
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Fleet on Rent</div>
            <div class="text-3xl font-black text-gray-950 mt-2">{{ $rentedVehicles }} of {{ $totalVehicles }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $availableVehicles }} available now</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Active Bookings</div>
            <div class="text-3xl font-black text-gray-950 mt-2">{{ $activeBookingsCount }}</div>
            <div class="text-xs text-gray-500 mt-1">Confirmed or currently rented</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Pending Payment Review</div>
            <div class="text-3xl font-black text-amber-600 mt-2">{{ $pendingReviewCount }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ $newInquiriesCount }} new inquiries</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Total Revenue</div>
            <div class="text-3xl font-black text-gray-950 mt-2">₱{{ number_format((float) $totalRevenue, 0) }}</div>
            <div class="text-xs text-gray-500 mt-1">Confirmed &amp; completed</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Recent Reservations --}}
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-950 text-sm">Recent reservations</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-red-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#F8F9FA] text-xs font-semibold text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3">Booking</th>
                            <th class="px-5 py-3">Renter</th>
                            <th class="px-5 py-3">Vehicle</th>
                            <th class="px-5 py-3">Dates</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse($recentBookings as $b)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-bold text-gray-950">{{ $b->booking_code }}</td>
                                <td class="px-5 py-3">{{ $b->renter_name }}</td>
                                <td class="px-5 py-3">{{ $b->vehicle?->name }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $b->pickup_datetime->format('M d') }} – {{ $b->return_datetime->format('M d') }}</td>
                                <td class="px-5 py-3"><x-status-badge :status="$b->status" /></td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.bookings.show', $b) }}" class="font-semibold text-red-600 hover:underline">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-gray-400">No bookings yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Fleet Status --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-bold text-gray-950 text-sm">Fleet status</h2>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="font-semibold text-emerald-800">Available</span>
                    <span class="font-black text-emerald-900">{{ $availableVehicles }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 border border-blue-200">
                    <span class="font-semibold text-blue-800">Rented</span>
                    <span class="font-black text-blue-900">{{ $rentedVehicles }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-200">
                    <span class="font-semibold text-red-800">Maintenance</span>
                    <span class="font-black text-red-900">{{ $maintenanceVehicles }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
