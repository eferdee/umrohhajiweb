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
        Schema::create('package_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('package_id')
                ->constrained('packages')
                ->restrictOnDelete(); // [FIX] dulunya cascadeOnDelete — hapus paket tidak lagi ikut menghapus jadwal & booking terkait

            $table->string('departure_city');

            $table->date('departure_date')->index(); // [FIX] index — sering dipakai untuk filter & sorting jadwal terdekat
            $table->date('return_date');

            $table->decimal('price', 15, 2);

            $table->unsignedInteger('quota');
            $table->unsignedInteger('available_seat');

            $table->boolean('status')->default(true)->index(); // [FIX] index untuk filter jadwal aktif

            $table->timestamps();

            // [FIX] cegah input jadwal keberangkatan yang persis sama dua kali untuk paket & kota yang sama
            $table->unique(
                ['package_id', 'departure_city', 'departure_date'],
                'pkg_schedule_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_schedules');
    }
};
