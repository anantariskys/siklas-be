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
         Schema::create('dosens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('gelar_awal');
            $table->string('gelar_akhir');
            $table->uuid('bidang_penelitian_major_id');
            $table->uuid('bidang_penelitian_minor_id')->nullable();
            $table->foreign('bidang_penelitian_major_id')->references('id')->on('bidang_penelitians');
            $table->foreign('bidang_penelitian_minor_id')->references('id')->on('bidang_penelitians');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
