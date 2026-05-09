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
            $table->unsignedBigInteger('student_id');
            $table->string('academic_year', 10);
            $table->string('level_class', 25);
            $table->unsignedBigInteger('subject_id');
            
            $table->decimal('average_value', 20, 2)->default(0);
            $table->string('mentor_note', 255)->nullable(); // Catatan Wali Kelas
            $table->integer('attendance')->default(0);

            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('academic_year')->references('academic_year')->on('academic_years')->onDelete('cascade');
            $table->foreign('level_class')->references('level_class')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
