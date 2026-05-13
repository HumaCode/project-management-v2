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
        Schema::create('catatans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('category')->comment('Personal, Project, Meeting, Technical, Task, Penting');
            $table->enum('priority', ['tinggi', 'sedang', 'rendah'])->default('sedang');
            $table->longText('content');
            $table->foreignUlid('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatans');
    }
};
