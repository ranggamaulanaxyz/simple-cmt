<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_report_rc_commissionings', function (Blueprint $table) {
            $table->foreignId('rc_direction_id')->nullable()->after('report_id')->constrained('task_report_rc_directions')->nullOnDelete();
            $table->dropColumn('arah_remote_control');
        });
    }

    public function down(): void
    {
        Schema::table('task_report_rc_commissionings', function (Blueprint $table) {
            $table->string('arah_remote_control')->nullable()->after('control_dcc_lbs');
            $table->dropForeign(['rc_direction_id']);
            $table->dropColumn('rc_direction_id');
        });
    }
};
