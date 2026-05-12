<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id');
            $table->string('category_subject', 150);
            $table->string('term', 20);
            $table->foreignId('class_id')->constrained('classes', 'class_id')->onDelete('cascade');
            $table->string('level_class', 100); // For display
            $table->foreign('category_subject')->references('category_subject')->on('categories_subject')->onDelete('cascade');
            $table->foreign('term')->references('term')->on('terms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
