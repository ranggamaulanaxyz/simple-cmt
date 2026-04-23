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
            $table->enum('status_admin', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu')->after('status');
            $table->enum('status_pimpinan', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu')->after('status_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_reports', function (Blueprint $table) {
            $table->dropColumn(['status_admin', 'status_pimpinan']);
        });
    }
};
