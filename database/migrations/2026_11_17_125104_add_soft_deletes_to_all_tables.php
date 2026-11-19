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
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('eksternals', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('pengujis', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('pasien_standars', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('eksternals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pengujis', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pasien_standars', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
