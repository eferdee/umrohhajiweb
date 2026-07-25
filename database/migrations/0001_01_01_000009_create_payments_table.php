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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete(); // dibiarkan cascade — pembayaran memang anak langsung dari satu booking

            $table->string('invoice_number')->unique();

            $table->enum('payment_type', [
                'dp',
                'full_payment',
                'installment',
                'refund'
            ])->default('full_payment');

            $table->enum('payment_method', [
                'bank_transfer',
                'cash',
                'credit_card',
                'debit_card',
                'qris'
            ])->default('bank_transfer');

            $table->decimal('amount', 15, 2);

            $table->string('transfer_proof')->nullable();

            $table->dateTime('payment_date')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('verified_at')->nullable();

            $table->enum('status', [
                'pending',
                'verified',
                'rejected',
                'refunded'
            ])->default('pending')->index(); // [FIX] index — sering difilter di dashboard admin

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes(); // [FIX] jangan hilangkan histori pembayaran secara permanen
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
