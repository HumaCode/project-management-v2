<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add temporary ulid column
        Schema::table('settings', function (Blueprint $table) {
            $table->ulid('ulid_id')->nullable();
        });

        // 2. Populate ULIDs for existing settings records
        $settings = DB::table('settings')->get();
        foreach ($settings as $setting) {
            DB::table('settings')
                ->where('id', $setting->id)
                ->update(['ulid_id' => strtolower((string) Str::ulid())]);
        }

        // 3. Drop primary key constraint, drop old id, rename ulid_id to id and set primary
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE settings DROP CONSTRAINT settings_pkey');
            DB::statement('ALTER TABLE settings DROP COLUMN id');
            DB::statement('ALTER TABLE settings RENAME COLUMN ulid_id TO id');
            DB::statement('ALTER TABLE settings ALTER COLUMN id SET NOT NULL');
            DB::statement('ALTER TABLE settings ADD PRIMARY KEY (id)');
        } else {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('id');
            });
            Schema::table('settings', function (Blueprint $table) {
                $table->renameColumn('ulid_id', 'id');
            });
            Schema::table('settings', function (Blueprint $table) {
                $table->primary('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE settings DROP CONSTRAINT settings_pkey');
            DB::statement('ALTER TABLE settings DROP COLUMN id');
            DB::statement('ALTER TABLE settings ADD COLUMN id BIGSERIAL PRIMARY KEY');
        } else {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropPrimary(['id']);
                $table->dropColumn('id');
            });
            Schema::table('settings', function (Blueprint $table) {
                $table->id()->first();
            });
        }
    }
};
