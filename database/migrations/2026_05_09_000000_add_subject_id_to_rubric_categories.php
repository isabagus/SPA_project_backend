<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rubric_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->after('rubric_id');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('rubric_categories', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};
