<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'gcash_enabled',
        'gcash_name',
        'gcash_number',
        'gcash_qr_path',
        'palawanpay_enabled',
        'palawanpay_name',
        'palawanpay_number',
        'palawanpay_qr_path',
        'bank_enabled',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_qr_path',
        'credit_card_enabled',
        'credit_card_fee_percentage',
        'down_payment_type',
        'down_payment_percentage',
        'down_payment_fixed_amount',
    ];

    protected $casts = [
        'gcash_enabled' => 'boolean',
        'palawanpay_enabled' => 'boolean',
        'bank_enabled' => 'boolean',
        'credit_card_enabled' => 'boolean',
        'credit_card_fee_percentage' => 'decimal:2',
        'down_payment_percentage' => 'decimal:2',
        'down_payment_fixed_amount' => 'decimal:2',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'gcash_enabled' => true,
            'gcash_name' => 'KLX Affordable Cars for Rent',
            'gcash_number' => '0907-883-8861',
            'palawanpay_enabled' => true,
            'palawanpay_name' => 'KLX Affordable Cars for Rent',
            'palawanpay_number' => '0907-883-8861',
            'bank_enabled' => true,
            'bank_name' => 'BDO / BPI',
            'bank_account_name' => 'KLX Affordable Cars for Rent',
            'bank_account_number' => '0012-3456-7890',
            'credit_card_enabled' => true,
            'credit_card_fee_percentage' => 6.00,
            'down_payment_type' => 'percentage',
            'down_payment_percentage' => 50.00,
            'down_payment_fixed_amount' => 5000.00,
        ]);
    }

    public function computeDownPayment(float $totalAmount): float
    {
        if ($this->down_payment_type === 'fixed') {
            return min($totalAmount, (float) $this->down_payment_fixed_amount);
        }

        return round($totalAmount * ((float) $this->down_payment_percentage / 100), 2);
    }
}
