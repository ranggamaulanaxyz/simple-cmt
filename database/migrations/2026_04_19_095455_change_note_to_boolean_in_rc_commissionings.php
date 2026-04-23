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
        Schema::table('task_report_rc_commissionings', function (Blueprint $table) {
            $table->boolean('note')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('task_report_rc_commissionings', function (Blueprint $table) {
            $table->text('note')->nullable()->change();
        });
    }
};
