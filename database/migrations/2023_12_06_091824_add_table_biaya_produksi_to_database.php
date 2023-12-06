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
        Schema::create('biaya_produksi', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_order');
            $table->integer('biaya_sales');
            $table->integer('biaya_desain');
            $table->integer('biaya_penanggung_jawab');
            $table->integer('biaya_pekerjaan');
            $table->integer('biaya_bpjs');
            $table->integer('biaya_transportasi');
            $table->integer('biaya_overhead');
            $table->integer('biaya_alat_listrik');
            $table->bigInteger('antrian_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_produksi');
    }
};
