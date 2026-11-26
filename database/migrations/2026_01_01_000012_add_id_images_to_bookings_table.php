<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('drivers_license_image_path')->nullable()->after('drivers_license_no');
            $table->string('government_id_image_path')->nullable()->after('government_id_no');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['drivers_license_image_path', 'government_id_image_path']);
        });
    }
};
