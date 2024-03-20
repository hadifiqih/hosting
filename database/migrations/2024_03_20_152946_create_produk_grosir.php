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
        Schema::create('produk_grosir', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id');
            $table->integer('min_qty');
            $table->integer('harga_grosir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_grosir');
    }
};
