<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'previous_return_datetime',
        'new_return_datetime',
        'extra_days',
        'rate_per_day',
        'total_added_amount',
        'notes',
        'approved_by',
    ];

    protected $casts = [
        'previous_return_datetime' => 'datetime',
        'new_return_datetime' => 'datetime',
        'rate_per_day' => 'decimal:2',
        'total_added_amount' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
