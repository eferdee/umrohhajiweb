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
        Schema::table('booking_pilgrims', function (Blueprint $table) {
            // Catatan admin saat menandai dokumen 'incomplete' — supaya jamaah/customer
            // tahu persis dokumen mana yang bermasalah dan apa yang perlu diperbaiki,
            // alih-alih cuma melihat badge merah tanpa konteks.
            $table->text('document_note')->nullable()->after('document_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_pilgrims', function (Blueprint $table) {
            $table->dropColumn('document_note');
        });
    }
};
