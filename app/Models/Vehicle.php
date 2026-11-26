<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'brand',
        'model',
        'year',
        'plate_number',
        'category',
        'transmission',
        'seats',
        'fuel_type',
        'daily_rate',
        'status',
        'is_featured',
        'description',
        'primary_image_path',
    ];

    protected $casts = [
        'year' => 'integer',
        'seats' => 'integer',
        'daily_rate' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class)->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->primary_image_path) {
            if (str_starts_with($this->primary_image_path, 'http')) {
                return $this->primary_image_path;
            }
            return asset('uploads/' . $this->primary_image_path);
        }

        return asset('images/kalix-icon.jpg');
    }
}
