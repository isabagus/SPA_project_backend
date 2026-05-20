<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('details_report', function (Blueprint $table) {
            // Tambahkan criteria_id sebagai FK baru
            $table->foreignId('criteria_id')->nullable()->constrained('rubric_criteria', 'criteria_id')->onDelete('cascade');
            
            // Jadikan rubric_id nullable karena penilaian sekarang akan fokus ke level criteria
            $table->unsignedBigInteger('rubric_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('details_report', function (Blueprint $table) {
            $table->dropForeign(['criteria_id']);
            $table->dropColumn('criteria_id');
            $table->foreignId('rubric_id')->nullable(false)->change();
        });
    }
};
