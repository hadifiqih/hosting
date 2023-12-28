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
        Schema::create('data_antrian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_order');
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('printfile_id');
            $table->dateTime('start_job')->nullable();
            $table->dateTime('end_job')->nullable();
            $table->unsignedBigInteger('desainer_id')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->unsignedBigInteger('finishing_id')->nullable();
            $table->unsignedBigInteger('qc_id')->nullable();
            $table->unsignedBigInteger('machine_id')->nullable();
            $table->tinyInteger('is_revision')->default(0);
            $table->string('spesifikasi')->nullable();
            $table->string('cabang_id')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->dateTime('done_production_at')->nullable();
            $table->unsignedBigInteger('estimator_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_antrian');
    }
};
