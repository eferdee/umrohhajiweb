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
        Schema::create('booking_pilgrims', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete(); // dibiarkan cascade — data jamaah memang anak langsung dari satu booking

            $table->string('full_name');
            $table->enum('gender', ['male', 'female']);

            $table->string('birth_place');
            $table->date('birth_date');

            // [FIX] NIK tidak lagi unique secara global — satu orang wajar mendaftar
            // di lebih dari satu booking (mis. Umroh tahun ini, Haji tahun depan).
            // Cukup diberi index biasa untuk mempercepat pencarian, plus dibatasi
            // agar tidak dobel dalam booking yang sama.
            $table->string('nik', 20)->index();

            $table->string('passport_number')->nullable();
            $table->date('passport_expired')->nullable();

            $table->string('passport_photo')->nullable();

            $table->string('ktp_photo')->nullable();

            $table->string('family_card_photo')->nullable();

            $table->string('phone', 20)->nullable();

            $table->text('address');

            $table->string('emergency_contact')->nullable();

            $table->string('relationship')->nullable();

            $table->string('photo')->nullable();

            $table->enum('document_status', [
                'incomplete',
                'pending',
                'verified'
            ])->default('incomplete')->index(); // [FIX] index untuk filter status dokumen di admin

            $table->timestamps();

            // [FIX] cegah NIK yang sama terdaftar dua kali dalam booking yang sama
            $table->unique(['booking_id', 'nik']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_pilgrims');
    }
};
