<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('package_categories')
                ->restrictOnDelete(); // [FIX] dulunya cascadeOnDelete — hapus kategori tidak lagi ikut menghapus paket

            $table->string('title');
            $table->string('slug')->unique();

            $table->string('airline')->nullable();
            $table->string('hotel_makkah')->nullable();
            $table->string('hotel_madinah')->nullable();

            $table->integer('duration'); // Hari

            $table->longText('description')->nullable();
            $table->longText('facilities')->nullable();
            $table->longText('itinerary')->nullable();

            $table->string('thumbnail')->nullable();

            $table->boolean('status')->default(true)->index(); // [FIX] index untuk filter paket aktif

            $table->timestamps();
            $table->softDeletes(); // [FIX] hapus logis, bukan hilang permanen dari database
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
