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
        Schema::create('details_subject', function (Blueprint $table) {
            $table->foreignId('subject_id')->references('subject_id')->on('subjects');
            $table->foreignId('teacher_id')->references('teacher_id')->on('teachers');
            $table->string('name_subject', 100);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_subject');
    }
};
