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
        Schema::create('dosen_minor_bidang', function (Blueprint $table) {
            $table->uuid('dosen_id');
            $table->uuid('bidang_penelitian_id');

            $table->primary(['dosen_id', 'bidang_penelitian_id']);

            $table->foreign('dosen_id')
                ->references('id')->on('dosens')
                ->cascadeOnDelete();

            $table->foreign('bidang_penelitian_id')
                ->references('id')->on('bidang_penelitians')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_minor_bidang');
    }
};
