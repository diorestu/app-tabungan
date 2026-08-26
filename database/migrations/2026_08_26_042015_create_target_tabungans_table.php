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
        Schema::create('target_tabungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->cascadeOnDelete();
            $table->string('nama_target');
            $table->string('kategori')->default('lainnya');
            $table->decimal('target_nominal', 15, 2);
            $table->decimal('terkumpul_nominal', 15, 2)->default(0);
            $table->date('tenggat_waktu')->nullable();
            $table->text('catatan')->nullable();
            $table->string('status')->default('berjalan'); // berjalan, tercapai, dibatalkan
            $table->timestamps();
        });

        Schema::create('target_tabungan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_tabungan_id')->constrained('target_tabungans')->cascadeOnDelete();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->cascadeOnDelete();
            $table->enum('tipe', ['alokasi', 'penarikan']); // alokasi dari saldo utama, atau penarikan kembali ke saldo utama
            $table->decimal('nominal', 15, 2);
            $table->decimal('saldo_target_sebelum', 15, 2);
            $table->decimal('saldo_target_sesudah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_tabungan_histories');
        Schema::dropIfExists('target_tabungans');
    }
};
