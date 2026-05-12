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
        Schema::create('classes', function (Blueprint $table) {
            $table->id('class_id'); // Auto-incrementing integer PK
            $table->string('level_name', 50); // e.g., 'Year 1'
            $table->string('section_name', 50)->default('-'); // e.g., 'A'
            $table->string('level_class', 100)->unique(); // Full name: 'Year 1-A'
            $table->foreignId('mentor_id')->nullable()->constrained('mentors', 'mentor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
