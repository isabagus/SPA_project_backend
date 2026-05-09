<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubric_categories', function (Blueprint $table) {
            $table->id('rubric_id'); 
            $table->foreignId('teacher_id')->constrained('teachers', 'teacher_id')->onDelete('cascade'); // Guru pengampu rubrik
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->onDelete('cascade');
            $table->string('term', 20);
            $table->string('rubric_name', 100);
            $table->foreign('term')->references('term')->on('terms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubric_categories');
    }
};
