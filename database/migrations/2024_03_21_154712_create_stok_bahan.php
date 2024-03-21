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
        Schema::create('stok_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->integer('jumlah_stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_bahan');
    }
};
