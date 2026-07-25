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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->enum('type', [
                'image',
                'video'
            ])->default('image');

            $table->string('file_path');

            $table->string('thumbnail')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->boolean('is_featured')->default(false);

            $table->boolean('is_published')->default(true)->index(); // [FIX] index — sering difilter di halaman publik

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
