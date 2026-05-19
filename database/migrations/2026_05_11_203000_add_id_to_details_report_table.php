<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('details_report', function (Blueprint $table) {
            // PostgreSQL tidak mendukung ->first(), kolom akan ditambahkan di akhir tabel
            $table->id();
        });
    }

    public function down(): void
    {
        Schema::table('details_report', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};
