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
        Schema::create('keranjang_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keranjang_id');
            $table->unsignedBigInteger('produk_id');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('diskon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang_item');
    }
};
