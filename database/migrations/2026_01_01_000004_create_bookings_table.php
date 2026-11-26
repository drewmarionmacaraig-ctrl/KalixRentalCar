<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();

            // Renter & Driver details (Contract Sections 1 & 8)
            $table->string('renter_name');
            $table->string('contact_no');
            $table->string('email')->nullable();
            $table->string('drivers_license_no');
            $table->string('government_id_no');
            $table->string('primary_driver_name');
            $table->string('primary_driver_license');
            $table->string('additional_driver_name')->nullable();
            $table->string('additional_driver_license')->nullable();

            // Trip details
            $table->dateTime('pickup_datetime');
            $table->dateTime('return_datetime');
            $table->dateTime('actual_return_datetime')->nullable();
            $table->string('pickup_location');
            $table->string('return_location');
            $table->string('route_intended_use')->nullable();

            // Pricing snapshots
            $table->decimal('daily_rate_snapshot', 10, 2);
            $table->unsignedSmallInteger('total_days');
            $table->decimal('base_amount', 10, 2);
            $table->decimal('addons_amount', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('processing_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('down_payment_amount', 10, 2);
            $table->decimal('remaining_balance', 10, 2);

            // Payment & Status
            $table->enum('payment_option', ['down_payment', 'full'])->default('down_payment');
            $table->enum('payment_method', ['gcash', 'palawanpay', 'bank_transfer', 'credit_card'])->default('gcash');
            $table->enum('status', [
                'pending_payment',
                'payment_submitted',
                'confirmed',
                'rented',
                'completed',
                'cancelled',
            ])->default('pending_payment');

            // Contract & Checklist
            $table->timestamp('contract_accepted_at')->nullable();
            $table->string('contract_signature_name')->nullable();
            $table->json('release_checklist')->nullable();
            $table->json('return_checklist')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
