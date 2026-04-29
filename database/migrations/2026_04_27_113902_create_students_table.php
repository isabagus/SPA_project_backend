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
            $table->id('id_student');
            $table->string('year_academy');
            $table->string('id_mentors');
            $table->string('name_student');
            $table->string('nis');
            $table->string('gender');
            $table->string('address');
            $table->string('photo');
            $table->string('email')->unique();
            $table->timestamps();
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
