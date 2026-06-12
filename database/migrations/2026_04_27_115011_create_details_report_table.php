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
        Schema::create('details_report', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports', 'report_id');
            $table->foreignId('rubric_id')->constrained('rubric_categories', 'rubric_id');
            $table->decimal('score', 5, 2);
            $table->text('description_subject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_report');
    }
};
