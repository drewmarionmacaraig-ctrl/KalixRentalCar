@props(['vehicle', 'pickup' => null, 'return' => null])

@php
    $isDateAvailable = $vehicle->getAttribute('is_available_for_dates');
    $effectiveStatus = $vehicle->status;
    if ($isDateAvailable === false && $effectiveStatus === 'available') {
        $effectiveStatus = 'rented';
    }
    $queryParams = array_filter([
        'pickup_datetime' => $pickup?->format('Y-m-d\TH:i'),
        'return_datetime' => $return?->format('Y-m-d\TH:i'),
    ]);
@endphp

<div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-gray-300 transition flex flex-col">
    <div class="relative aspect-[16/10] bg-gray-100 overflow-hidden">
        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        <div class="absolute top-3 left-3">
            <x-status-badge :status="$effectiveStatus" />
        </div>
        <div class="absolute top-3 right-3 bg-black/75 text-white text-[11px] font-semibold px-2.5 py-1 rounded-md">
            {{ $vehicle->category }}
        </div>
    </div>

    <div class="p-5 flex-1 flex flex-col justify-between gap-4">
        <div>
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-bold text-gray-950 text-lg leading-snug">{{ $vehicle->name }}</h3>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ $vehicle->year }} &bull; {{ $vehicle->plate_number }}</p>

            <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-600">
                <span class="inline-flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded-md font-medium">
                    {{ $vehicle->transmission }}
                </span>
                <span class="inline-flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded-md font-medium">
                    {{ $vehicle->seats }} Seats
                </span>
                <span class="inline-flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded-md font-medium">
                    {{ $vehicle->fuel_type }}
                </span>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <div>
                <span class="text-xl font-black text-gray-950">₱{{ number_format((float) $vehicle->daily_rate, 0) }}</span>
                <span class="text-xs text-gray-500">/ day</span>
            </div>

            @if($effectiveStatus === 'available')
                <a href="{{ route('catalog.show', array_merge(['vehicle' => $vehicle], $queryParams)) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                    Book now
                </a>
            @else
                <a href="{{ route('catalog.show', array_merge(['vehicle' => $vehicle], $queryParams)) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold transition">
                    Check other dates
                </a>
            @endif
        </div>
    </div>
</div>
