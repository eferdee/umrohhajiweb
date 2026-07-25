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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            $table->string('category')->default('General')->index(); // [FIX] index — sering dipakai untuk grouping/filter FAQ

            $table->string('question');

            $table->text('answer');

            $table->unsignedInteger('views')->default(0);

            $table->unsignedInteger('sort_order')->default(0);

            $table->boolean('is_published')->default(true)->index(); // [FIX] index — chatbot & halaman publik hanya ambil yang published

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
