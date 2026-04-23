<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_reports', function (Blueprint $table) {
            $table->id();

            // General fields
            $table->enum('type', ['rc', 'gfd']);
            $table->date('date');
            $table->enum('status', ['draft', 'disubmit', 'terverifikasi', 'ditolak', 'direview'])->default('draft');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('gardu');
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('arah')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_note')->nullable();

            // RC-specific fields
            $table->string('rtu')->nullable();
            $table->string('modem')->nullable();

            // GFD-specific fields
            $table->string('task_type')->nullable();
            $table->string('penyulang')->nullable();
            $table->string('gardu_induk')->nullable();
            $table->string('old_gfd')->nullable();
            $table->string('old_gfd_type_serial_number')->nullable();
            $table->string('old_gfd_setting_kepekaan_arus')->nullable();
            $table->string('old_gfd_setting_kepekaan_waktu')->nullable();
            $table->string('old_gfd_setting_waktu')->nullable();
            $table->string('old_gfd_injek_arus')->nullable();
            $table->string('old_gfd_condition')->nullable();
            $table->string('new_gfd')->nullable();
            $table->string('new_gfd_type_serial_number')->nullable();
            $table->string('new_gfd_setting_kepekaan_arus')->nullable();
            $table->string('new_gfd_setting_kepekaan_waktu')->nullable();
            $table->string('new_gfd_setting_waktu')->nullable();
            $table->string('new_gfd_injek_arus')->nullable();
            $table->string('new_gfd_condition')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_reports');
    }
};
