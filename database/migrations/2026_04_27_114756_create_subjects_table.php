<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id'); // PK Integer Auto Increment
            $table->string('category_subject', 100); // "Mathematics", dll
            $table->string('term', 20); // FK ke tabel Terms
            $table->string('level_class', 25); // FK ke tabel Classes (level_class)
            
            $table->foreign('term')->references('term')->on('terms')->onDelete('cascade');
            $table->foreign('level_class')->references('level_class')->on('classes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
