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
            $table->integer('id_kategori_bahan');
            $table->string('kode_produk');
            $table->string('nama_produk');
            $table->string('harga_produk');
            $table->string('harga_kulak');
            $table->string('stok_1');
            $table->string('stok_2');
            $table->string('stok_3');
            $table->string('stok_4');
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
