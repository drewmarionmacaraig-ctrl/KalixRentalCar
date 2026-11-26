<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code', 80)->unique();
            $table->string('name', 120);
            $table->string('account_name', 120)->nullable();
            $table->string('account_number', 80)->nullable();
            $table->string('instructions', 255)->nullable();
            $table->string('qr_path')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Allow custom payment method codes on bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method', 80)->default('gcash')->change();
        });

        // Migrate existing payment_settings rows into payment_methods if present
        $setting = DB::table('payment_settings')->first();
        if ($setting) {
            $now = now();
            DB::table('payment_methods')->insert([
                [
                    'code' => 'gcash',
                    'name' => 'GCash',
                    'account_name' => $setting->gcash_name ?? 'KLX Affordable Cars for Rent',
                    'account_number' => $setting->gcash_number ?? '0907-883-8861',
                    'instructions' => null,
                    'qr_path' => $setting->gcash_qr_path ?? null,
                    'is_enabled' => (bool) ($setting->gcash_enabled ?? true),
                    'sort_order' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'code' => 'palawanpay',
                    'name' => 'PalawanPay',
                    'account_name' => $setting->palawanpay_name ?? 'KLX Affordable Cars for Rent',
                    'account_number' => $setting->palawanpay_number ?? '0907-883-8861',
                    'instructions' => null,
                    'qr_path' => $setting->palawanpay_qr_path ?? null,
                    'is_enabled' => (bool) ($setting->palawanpay_enabled ?? true),
                    'sort_order' => 2,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'code' => 'bank_transfer',
                    'name' => $setting->bank_name ? 'Bank Transfer (' . $setting->bank_name . ')' : 'Bank Transfer',
                    'account_name' => $setting->bank_account_name ?? 'KLX Affordable Cars for Rent',
                    'account_number' => $setting->bank_account_number ?? '0012-3456-7890',
                    'instructions' => null,
                    'qr_path' => $setting->bank_qr_path ?? null,
                    'is_enabled' => (bool) ($setting->bank_enabled ?? true),
                    'sort_order' => 3,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
