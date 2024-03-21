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
        Schema::create('produk_harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk');
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->integer('harga_kulak');
            $table->integer('harga_jual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_harga');
    }
};
