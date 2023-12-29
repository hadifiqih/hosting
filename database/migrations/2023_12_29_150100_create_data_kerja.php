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
        Schema::create('data_kerja', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_order');
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai');
            $table->unsignedBigInteger('desainer_id');
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('operator_id');
            $table->unsignedBigInteger('finishing_id');
            $table->unsignedBigInteger('qc_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kerja');
    }
};
