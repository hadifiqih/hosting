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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_order');
            $table->string('metode_pembayaran');
            $table->unsignedDecimal('biaya_packing', $precision = 10);
            $table->unsignedDecimal('biaya_pasang', $precision = 10);
            $table->unsignedDecimal('diskon', $precision = 10);
            $table->unsignedDecimal('total_harga', $precision = 10);
            $table->unsignedDecimal('dibayarkan', $precision = 10);
            $table->tinyInteger('status_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
