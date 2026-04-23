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
        Schema::table('gfd_inspection_items', function (Blueprint $table) {
            $table->boolean('is_value_type')->default(false)->after('name');
            $table->string('unit')->nullable()->after('is_value_type');
        });

        Schema::table('task_report_gfd_inspections', function (Blueprint $table) {
            $table->string('value')->nullable()->after('rusak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gfd_inspection_items', function (Blueprint $table) {
            $table->dropColumn(['is_value_type', 'unit']);
        });

        Schema::table('task_report_gfd_inspections', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }
};
