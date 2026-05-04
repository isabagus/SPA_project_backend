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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('academic_year', 20);
            $table->string('level_class', 20);
            $table->string('religion_name', 20);
            $table->foreignId('mentor_id')->constrained('mentors', 'mentor_id');
            $table->string('name_student');
            // $table->string('nis');
            $table->string('gender', 15);
            $table->string('address');
            $table->string('phone_number', 15);
            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('level_class')->references('level_class')->on('classes')->onDelete('cascade');
            $table->foreign('academic_year')->references('academic_year')->on('academic_years')->onDelete('cascade');
            $table->foreign('religion_name')->references('religion_name')->on('religions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
