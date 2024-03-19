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
        Schema::create('keranjang_bahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomor_invoice');
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('produk_id');
            $table->string('qty');
            $table->string('diskon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang_bahan');
    }
};
