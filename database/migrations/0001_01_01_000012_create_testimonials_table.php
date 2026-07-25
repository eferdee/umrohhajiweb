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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->nullOnDelete();

            $table->string('name');

            $table->string('city')->nullable();

            $table->string('package_name')->nullable();

            $table->string('photo')->nullable();

            $table->unsignedTinyInteger('rating');

            $table->text('testimonial');

            $table->boolean('is_featured')->default(false);

            $table->boolean('is_published')->default(false)->index(); // [FIX] index — sering difilter di halaman publik

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
