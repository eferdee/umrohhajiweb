<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('tracking_code', 20)->nullable()->unique()->after('id');
            $table->text('reply_message')->nullable()->after('admin_notes');
        });

        // Backfill tracking_code for any existing rows so the unique index stays valid.
        DB::table('contact_messages')->whereNull('tracking_code')->orderBy('id')->get()->each(function ($row) {
            DB::table('contact_messages')->where('id', $row->id)->update([
                'tracking_code' => 'MSG-' . strtoupper(Str::random(8)),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['tracking_code', 'reply_message']);
        });
    }
};
