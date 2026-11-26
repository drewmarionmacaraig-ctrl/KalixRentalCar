<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'vehicle_id',
        'renter_name',
        'contact_no',
        'email',
        'drivers_license_no',
        'drivers_license_image_path',
        'government_id_no',
        'government_id_image_path',
        'primary_driver_name',
        'primary_driver_license',
        'additional_driver_name',
        'additional_driver_license',
        'pickup_datetime',
        'return_datetime',
        'actual_return_datetime',
        'pickup_location',
        'return_location',
        'route_intended_use',
        'daily_rate_snapshot',
        'total_days',
        'base_amount',
        'addons_amount',
        'delivery_fee',
        'processing_fee',
        'total_amount',
        'down_payment_amount',
        'remaining_balance',
        'payment_option',
        'payment_method',
        'status',
        'contract_accepted_at',
        'contract_signature_name',
        'release_checklist',
        'return_checklist',
        'admin_notes',
    ];

    protected $casts = [
        'pickup_datetime' => 'datetime',
        'return_datetime' => 'datetime',
        'actual_return_datetime' => 'datetime',
        'contract_accepted_at' => 'datetime',
        'daily_rate_snapshot' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'addons_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'release_checklist' => 'array',
        'return_checklist' => 'array',
    ];

    public const BLOCKING_STATUSES = [
        'payment_submitted',
        'confirmed',
        'rented',
    ];

    public static function generateCode(): string
    {
        $year = now()->format('Y');

        do {
            $code = sprintf('KLX-%s-%s', $year, strtoupper(\Illuminate\Support\Str::random(6)));
        } while (static::where('booking_code', $code)->exists());

        return $code;
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BookingPayment::class)->latest();
    }

    public function extensions(): HasMany
    {
        return $this->hasMany(BookingExtension::class)->latest();
    }

    public function getAmountDueNowAttribute(): float
    {
        return $this->payment_option === 'full'
            ? (float) $this->total_amount
            : (float) $this->down_payment_amount;
    }

    public function getDriversLicenseImageUrlAttribute(): ?string
    {
        return $this->drivers_license_image_path ? asset('uploads/' . $this->drivers_license_image_path) : null;
    }

    public function getGovernmentIdImageUrlAttribute(): ?string
    {
        return $this->government_id_image_path ? asset('uploads/' . $this->government_id_image_path) : null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'Pending Payment',
            'payment_submitted' => 'Payment Verification',
            'confirmed' => 'Confirmed',
            'rented' => 'Rented',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }
}
