<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dompet_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->default('Mandiri');
            $table->string('nomor_rekening');
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        DB::table('dompet_kas')->insert([
            'nama' => 'Mandiri',
            'nomor_rekening' => '140-00-1234567-8',
            'saldo' => 0,
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dompet_kas');
    }
};
