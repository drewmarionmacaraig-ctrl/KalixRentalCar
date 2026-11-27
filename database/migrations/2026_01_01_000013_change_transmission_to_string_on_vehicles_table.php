<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('transmission', 40)->default('Automatic')->change();
        });
    }

    public function down(): void
    {
        // Keep as string to preserve custom transmissions
    }
};
