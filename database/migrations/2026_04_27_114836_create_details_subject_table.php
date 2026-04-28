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
            $table->string("id_subject");
            $table->string("id_teacher");
            $table->string("sub_module");

            $table->foreignId('id_subject')->references('id_subject')->on('subjects');
            $table->foreignId('id_teacher')->references('id_teacher')->on('teachers');
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
