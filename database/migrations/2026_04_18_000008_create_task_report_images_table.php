<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_report_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('task_reports')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->string('image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_report_images');
    }
};
