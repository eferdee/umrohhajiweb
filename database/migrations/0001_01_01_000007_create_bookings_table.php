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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->string('booking_code')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete(); // dibiarkan cascade — wajar kalau akun user dihapus, booking miliknya ikut terhapus

            $table->foreignId('package_schedule_id')
                ->constrained('package_schedules')
                ->restrictOnDelete(); // [FIX] dulunya cascadeOnDelete — jadwal tidak bisa dihapus selama masih ada booking aktif

            $table->date('booking_date');

            $table->dateTime('payment_deadline')->nullable();

            $table->unsignedInteger('total_people')->default(1);

            $table->decimal('total_price', 15, 2);

            $table->enum('status', [
                'pending',
                'waiting_payment',
                'waiting_verification',
                'paid',
                'scheduled',
                'completed',
                'cancelled'
            ])->default('pending')->index(); // [FIX] index — sering difilter di dashboard admin

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes(); // [FIX] jangan hilangkan histori booking/transaksi secara permanen
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
