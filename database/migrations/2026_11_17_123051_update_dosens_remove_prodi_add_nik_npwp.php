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
        Schema::table('dosens', function (Blueprint $table) {
            if (Schema::hasColumn('dosens', 'program_studi')) {
                $table->dropColumn('program_studi');
            }
            if (!Schema::hasColumn('dosens', 'nik')) {
                $table->string('nik', 30)->nullable()->index()->after('nuptk');
            }
            if (!Schema::hasColumn('dosens', 'npwp')) {
                $table->string('npwp', 30)->nullable()->index()->after('nik');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'program_studi')) {
                $table->string('program_studi')->nullable()->after('bagian');
            }
            if (Schema::hasColumn('dosens', 'npwp')) {
                $table->dropColumn('npwp');
            }
            if (Schema::hasColumn('dosens', 'nik')) {
                $table->dropColumn('nik');
            }
        });
    }
};
