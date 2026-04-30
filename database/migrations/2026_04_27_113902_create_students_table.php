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
            $table->date('academic_year');
            $table->foreignId('mentor_id')->constrained('mentors', 'mentor_id');
            $table->string('name_student');
            $table->string('nis');
            $table->string('gender');
            $table->string('address');
            $table->string('photo')->nullable();
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('academic_year')->references('academic_year')->on('academic_years')->onDelete('cascade');
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
