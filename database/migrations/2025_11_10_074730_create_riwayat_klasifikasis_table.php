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
        Schema::create('riwayat_klasifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('judul')->nullable();
            $table->text('abstrak')->nullable();
            $table->string('prediksi_topik');
            $table->float('confidence_score')->nullable();
            $table->timestamp('diklasifikasi_pada')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_klasifikasis');
    }
};
