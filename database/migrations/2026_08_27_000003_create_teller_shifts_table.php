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
        Schema::create('teller_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('shift_date')->index();
            $table->decimal('modal_awal', 15, 2)->default(0);
            $table->decimal('total_setoran', 15, 2)->default(0);
            $table->decimal('total_penarikan', 15, 2)->default(0);
            $table->decimal('saldo_sistem', 15, 2)->default(0);
            $table->decimal('saldo_fisik', 15, 2)->default(0);
            $table->decimal('selisih', 15, 2)->default(0);
            $table->json('pecahan_uang')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['buka', 'ditutup', 'disetujui'])->default('buka')->index();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teller_shifts');
    }
};
