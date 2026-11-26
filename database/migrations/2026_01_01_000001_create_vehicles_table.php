<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year')->default(2024);
            $table->string('plate_number')->unique();
            $table->string('category')->default('MPV');
            $table->enum('transmission', ['Automatic', 'Manual'])->default('Automatic');
            $table->unsignedTinyInteger('seats')->default(5);
            $table->string('fuel_type')->default('Gasoline');
            $table->decimal('daily_rate', 10, 2);
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->text('description')->nullable();
            $table->string('primary_image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
