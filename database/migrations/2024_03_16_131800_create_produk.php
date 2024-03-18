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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kategori_bahan')->default(1);
            $table->string('kode_produk');
            $table->string('nama_produk');
            $table->string('harga_jual');
            $table->string('harga_kulak');
            $table->integer('stok_1')->default(0);
            $table->integer('stok_2')->default(0);
            $table->integer('stok_3')->default(0);
            $table->integer('stok_4')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
