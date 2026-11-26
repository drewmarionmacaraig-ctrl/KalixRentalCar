<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\PaymentSetting;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PricingService
{
    public function calculateDays(Carbon $pickup, Carbon $return): int
    {
        $hours = max(1, $pickup->diffInMinutes($return) / 60);
        return (int) max(1, ceil($hours / 24));
    }

    /**
     * Compute full booking price breakdown.
     *
     * @param  Collection<int, Addon>  $addons
     * @return array<string, mixed>
     */
    public function calculateBreakdown(
        Vehicle $vehicle,
        Carbon $pickup,
        Carbon $return,
        Collection $addons,
        string $paymentOption = 'down_payment',
        string $paymentMethod = 'gcash',
        ?PaymentSetting $settings = null
    ): array {
        $settings ??= PaymentSetting::current();
        $days = $this->calculateDays($pickup, $return);
        $dailyRate = (float) $vehicle->daily_rate;
        $baseAmount = round($dailyRate * $days, 2);

        $addonLines = [];
        $addonsAmount = 0.0;

        foreach ($addons as $addon) {
            $unitPrice = (float) $addon->price;
            $subtotal = $addon->pricing_type === 'per_day'
                ? round($unitPrice * $days, 2)
                : round($unitPrice, 2);

            $addonsAmount += $subtotal;
            $addonLines[] = [
                'addon' => $addon,
                'name_snapshot' => $addon->name,
                'pricing_type_snapshot' => $addon->pricing_type,
                'unit_price_snapshot' => $unitPrice,
                'days' => $addon->pricing_type === 'per_day' ? $days : 1,
                'subtotal' => $subtotal,
            ];
        }

        $subtotalBeforeFee = $baseAmount + $addonsAmount;
        $processingFee = 0.0;
        if ($paymentMethod === 'credit_card' && $settings->credit_card_enabled) {
            $processingFee = round($subtotalBeforeFee * ((float) $settings->credit_card_fee_percentage / 100), 2);
        }

        $totalAmount = round($subtotalBeforeFee + $processingFee, 2);
        $downPaymentAmount = $paymentOption === 'full'
            ? $totalAmount
            : $settings->computeDownPayment($totalAmount);

        $remainingBalance = round(max(0, $totalAmount - $downPaymentAmount), 2);

        return [
            'days' => $days,
            'daily_rate' => $dailyRate,
            'base_amount' => $baseAmount,
            'addons_amount' => round($addonsAmount, 2),
            'addon_lines' => $addonLines,
            'processing_fee' => $processingFee,
            'total_amount' => $totalAmount,
            'down_payment_amount' => $downPaymentAmount,
            'remaining_balance' => $remainingBalance,
            'amount_due_now' => $paymentOption === 'full' ? $totalAmount : $downPaymentAmount,
        ];
    }
}
