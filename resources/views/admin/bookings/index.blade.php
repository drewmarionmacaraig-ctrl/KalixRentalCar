@extends('layouts.admin')

@section('header', 'Bookings')

@section('content')
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                @foreach([
                    '' => 'All',
                    'payment_submitted' => 'Payment Review',
                    'confirmed' => 'Confirmed',
                    'rented' => 'Rented',
                    'completed' => 'Completed',
                    'pending_payment' => 'Pending Payment',
                    'cancelled' => 'Cancelled',
                ] as $val => $lbl)
                    <a href="{{ route('admin.bookings.index', array_filter(['status' => $val, 'search' => request('search')])) }}"
                       class="px-3 py-1.5 rounded-lg {{ request('status', '') === $val ? 'bg-gray-950 text-white' : 'bg-gray-100 text-gray-600' }}">
                        {{ $lbl }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code or renter..." class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs focus:border-red-600 focus:ring-red-600">
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-gray-950 text-white text-xs font-semibold cursor-pointer">Search</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#F8F9FA] text-xs font-semibold text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3">Booking Code</th>
                        <th class="px-5 py-3">Renter</th>
                        <th class="px-5 py-3">Vehicle</th>
                        <th class="px-5 py-3">Schedule</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-bold text-gray-950">{{ $b->booking_code }}</td>
                            <td class="px-5 py-3">
                                <div class="font-semibold text-gray-900">{{ $b->renter_name }}</div>
                                <div class="text-gray-500">{{ $b->contact_no }}</div>
                            </td>
                            <td class="px-5 py-3">{{ $b->vehicle?->name }}</td>
                            <td class="px-5 py-3 text-gray-600">
                                {{ $b->pickup_datetime->format('M d, g:i A') }}<br>
                                <span class="text-gray-400">to {{ $b->return_datetime->format('M d, g:i A') }}</span>
                            </td>
                            <td class="px-5 py-3 font-bold text-gray-950">₱{{ number_format((float) $b->total_amount, 2) }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$b->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.bookings.show', $b) }}" class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold transition">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-400">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
@endsection
