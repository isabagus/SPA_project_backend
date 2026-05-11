<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubric_criteria', function (Blueprint $table) {
            $table->id('criteria_id');
            $table->foreignId('rubric_id')->constrained('rubric_categories', 'rubric_id')->onDelete('cascade');
            $table->string('criteria_name');
            $table->text('default_description')->nullable(); // Opsional: deskripsi standar untuk indikator ini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubric_criteria');
    }
};
