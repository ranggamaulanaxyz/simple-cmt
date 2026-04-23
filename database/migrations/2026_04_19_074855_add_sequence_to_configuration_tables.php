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
        Schema::table('rc_type_signals', function (Blueprint $table) {
            $table->integer('sequence')->default(0);
        });
        Schema::table('rc_points', function (Blueprint $table) {
            $table->integer('sequence')->default(0);
        });
        Schema::table('gfd_inspection_items', function (Blueprint $table) {
            $table->integer('sequence')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rc_type_signals', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
        Schema::table('rc_points', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
        Schema::table('gfd_inspection_items', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
    }
};
