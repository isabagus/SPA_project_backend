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
        Schema::create('details_report', function (Blueprint $table) {
            $table->foreignId('report_id')->constrained('reports', 'report_id');
            $table->foreignId('mentor_id')->constrained('mentors', 'mentor_id');
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id');
            $table->text('description');
            $table->timestamps();
        });
        Schema::table('details_report', function (Blueprint $table) {
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_report');
    }
};
