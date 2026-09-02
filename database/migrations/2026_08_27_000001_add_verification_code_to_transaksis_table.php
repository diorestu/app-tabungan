<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('verification_code', 64)->nullable()->unique()->after('kode_transaksi');
        });

        // Populate existing transactions with a secure verification code
        $transaksis = DB::table('transaksis')->get();
        foreach ($transaksis as $trx) {
            DB::table('transaksis')
                ->where('id', $trx->id)
                ->update([
                    'verification_code' => strtoupper(substr(hash('sha256', $trx->kode_transaksi . '|' . $trx->id . '|' . $trx->nominal . '|' . Str::random(16)), 0, 24)),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('verification_code');
        });
    }
};
