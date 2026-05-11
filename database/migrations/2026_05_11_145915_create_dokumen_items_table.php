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
        Schema::create('dokumen_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('dokumen_id')->constrained('dokumens')->onDelete('cascade');
            $table->string('type'); // text, image, code, file
            $table->longText('content')->nullable(); // text content or code snippet
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_items');
    }
};
