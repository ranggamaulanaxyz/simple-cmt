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
        Schema::table('task_reports', function (Blueprint $table) {
            $table->text('commissioning_notes')->nullable()->after('modem');
        });
    }

    public function down(): void
    {
        Schema::table('task_reports', function (Blueprint $table) {
            $table->dropColumn('commissioning_notes');
        });
    }
};
