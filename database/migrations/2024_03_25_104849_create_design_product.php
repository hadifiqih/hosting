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
        Schema::create('design_queue', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->foreignId('sales_id')->constrained('sales');
            $table->foreignId('job_id')->constrained('jobs');
            $table->foreignId('designer_id')->constrained('users');
            $table->string('file_cetak');
            $table->string('ref_desain');
            $table->text('note');
            $table->tinyInteger('prioritas')->default(0);
            $table->timestamp('start_design')->nullable();
            $table->timestamp('end_design')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('toWorkshop')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_queue');
    }
};
