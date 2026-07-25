<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan status 'partially_paid' agar booking yang baru dibayar
     * sebagian (DP/cicilan) tidak otomatis dianggap 'paid' (lunas).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY status ENUM(
            'pending',
            'waiting_payment',
            'waiting_verification',
            'partially_paid',
            'paid',
            'scheduled',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Turunkan booking yang sedang 'partially_paid' balik ke 'waiting_verification'
        // dulu supaya tidak ada baris dengan nilai enum yang bakal dihapus.
        DB::table('bookings')->where('status', 'partially_paid')->update(['status' => 'waiting_verification']);

        DB::statement("ALTER TABLE bookings MODIFY status ENUM(
            'pending',
            'waiting_payment',
            'waiting_verification',
            'paid',
            'scheduled',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }
};
