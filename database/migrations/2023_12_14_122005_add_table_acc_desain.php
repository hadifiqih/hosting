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
        Schema::create('accDesain', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_order');
            $table->bigInteger('sales_id');
            $table->text('filename');
            $table->text('filepath');
            $table->text('filetype');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accDesain');
    }
};
