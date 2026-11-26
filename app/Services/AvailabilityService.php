<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Vehicle;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Check if a vehicle is available for the given date/time window.
     */
    public function isVehicleAvailable(
        Vehicle $vehicle,
        Carbon $pickup,
        Carbon $return,
        ?int $ignoreBookingId = null
    ): bool {
        if ($vehicle->status === 'maintenance') {
            return false;
        }

        return ! $this->findConflictingBooking($vehicle, $pickup, $return, $ignoreBookingId);
    }

    /**
     * Find the first overlapping booking for a vehicle, if any.
     */
    public function findConflictingBooking(
        Vehicle $vehicle,
        Carbon $pickup,
        Carbon $return,
        ?int $ignoreBookingId = null
    ): ?Booking {
        return Booking::query()
            ->where('vehicle_id', $vehicle->id)
            ->whereIn('status', Booking::BLOCKING_STATUSES)
            ->when($ignoreBookingId, fn ($q) => $q->where('id', '!=', $ignoreBookingId))
            ->where('pickup_datetime', '<', $return)
            ->where('return_datetime', '>', $pickup)
            ->orderBy('pickup_datetime')
            ->first();
    }
}
