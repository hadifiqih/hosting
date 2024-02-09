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
        Schema::create('iklan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_iklan');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->bigInteger('user_id');
            $table->bigInteger('job_id');
            $table->bigInteger('sales_id');
            $table->string('platform');
            $table->decimal('budget');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iklan');
    }
};
