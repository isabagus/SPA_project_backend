<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id'); 
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade');
            $table->string('academic_year', 20);
            $table->foreignId('class_id')->constrained('classes', 'class_id')->onDelete('cascade');
            $table->string('level_class', 100); // For display
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->onDelete('cascade');
            
            $table->decimal('average_value', 20, 2)->default(0);
            $table->text('mentor_note')->nullable(); // Changed to text for longer evaluation
            $table->integer('attendance')->default(0);

            $table->foreign('academic_year')->references('academic_year')->on('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
