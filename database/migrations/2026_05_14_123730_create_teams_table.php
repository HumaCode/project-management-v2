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
        Schema::create('teams', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignUlid('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->foreignUlid('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role')->nullable()->comment('Contoh: Designer, Backend, Frontend');
            $table->primary(['team_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }
};
