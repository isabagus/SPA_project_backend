<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubric_categories', function (Blueprint $table) {
            $table->id('rubric_id'); // PK Integer
            $table->foreignId('teacher_id')->constrained('teachers', 'teacher_id')->onDelete('cascade'); // Guru pengampu rubrik
            $table->string('term', 20); // Term rubrik (Term 1, Term 2, dll)
            $table->string('rubric_name', 100); // "Shapes and Patterns", "Numbers To 20", dll
            
            // $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->onDelete('cascade');
            $table->foreign('term')->references('term')->on('terms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubric_categories');
    }
};
