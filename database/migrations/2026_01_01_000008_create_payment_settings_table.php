<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('gcash_enabled')->default(true);
            $table->string('gcash_name')->nullable();
            $table->string('gcash_number')->nullable();
            $table->string('gcash_qr_path')->nullable();

            $table->boolean('palawanpay_enabled')->default(true);
            $table->string('palawanpay_name')->nullable();
            $table->string('palawanpay_number')->nullable();
            $table->string('palawanpay_qr_path')->nullable();

            $table->boolean('bank_enabled')->default(true);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_qr_path')->nullable();

            $table->boolean('credit_card_enabled')->default(true);
            $table->decimal('credit_card_fee_percentage', 5, 2)->default(6.00);

            $table->enum('down_payment_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('down_payment_percentage', 5, 2)->default(50.00);
            $table->decimal('down_payment_fixed_amount', 10, 2)->default(5000.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
