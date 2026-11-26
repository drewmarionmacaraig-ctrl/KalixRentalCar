@extends('layouts.admin')

@section('header', 'Reports')

@section('actions')
    <a href="{{ route('admin.reports.export') }}" class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-800 text-xs font-semibold transition">
        Export CSV
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Total Revenue</div>
            <div class="text-3xl font-black text-gray-950 mt-2">₱{{ number_format((float) $totalRevenue, 0) }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Confirmed / Completed Bookings</div>
            <div class="text-3xl font-black text-gray-950 mt-2">{{ $totalBookings }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Avg. Rental Duration</div>
            <div class="text-3xl font-black text-gray-950 mt-2">{{ $avgDuration }} days</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="text-xs font-semibold text-gray-500">Current Fleet Utilization</div>
            <div class="text-3xl font-black text-red-600 mt-2">{{ $utilization }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-7 bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-950 text-sm mb-6">Revenue by month</h2>
            @php $maxRev = max(1, (float) ($monthlyRevenue->max('revenue') ?? 1)); @endphp
            <div class="flex items-end gap-4 h-52 pt-4">
                @forelse($monthlyRevenue as $row)
                    @php $pct = max(8, round(((float) $row->revenue / $maxRev) * 100)); @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                        <div class="text-[11px] font-bold text-gray-700">₱{{ number_format((float) $row->revenue, 0) }}</div>
                        <div class="w-full max-w-[48px] rounded-t-lg {{ $loop->last ? 'bg-red-600' : 'bg-gray-300' }}" style="height: {{ $pct }}%"></div>
                        <div class="text-[11px] font-medium text-gray-500">{{ $row->month }}</div>
                    </div>
                @empty
                    <div class="w-full text-center text-sm text-gray-400 self-center">No revenue data yet.</div>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-5 bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-950 text-sm mb-4">Top vehicles</h2>
            <div class="divide-y divide-gray-100 text-xs">
                @foreach($topVehicles as $v)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-gray-950">{{ $v->name }}</div>
                            <div class="text-gray-500">{{ $v->bookings_count }} booking(s)</div>
                        </div>
                        <div class="font-black text-gray-950">₱{{ number_format((float) ($v->bookings_sum_total_amount ?? 0), 0) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
