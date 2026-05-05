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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id')->primary();
            $table->string('category_subject', 150);
            $table->string('term', 20);
            // $table->string('name_subject');

            $table->foreign('category_subject')->references('category_subject')->on('categories_subject')->onDelete('cascade');
            $table->foreign('term')->references('term')->on('terms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
