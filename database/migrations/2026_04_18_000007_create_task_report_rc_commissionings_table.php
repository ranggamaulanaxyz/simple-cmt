<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_report_rc_commissionings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('task_reports')->cascadeOnDelete();
            $table->string('database_field')->nullable();
            $table->foreignId('point_id')->nullable()->constrained('rc_points')->nullOnDelete();
            $table->string('terminal_kubiker')->nullable();
            $table->boolean('signaling_gh')->default(false);
            $table->boolean('signaling_dcc')->default(false);
            $table->boolean('control_dcc_rtu')->default(false);
            $table->boolean('control_dcc_rele_plat')->default(false);
            $table->boolean('control_dcc_lbs')->default(false);
            $table->string('arah_remote_control')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_report_rc_commissionings');
    }
};
