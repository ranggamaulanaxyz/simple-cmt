<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_report_gfd_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('task_reports')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('gfd_inspection_items')->cascadeOnDelete();
            $table->boolean('ada')->default(false);
            $table->boolean('tidak_ada')->default(false);
            $table->boolean('rusak')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_report_gfd_inspections');
    }
};
