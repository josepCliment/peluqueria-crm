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
        Schema::create('ticket_servicio', function (Blueprint $table) {
            $table->id('pivot_id');
            $table->foreignId('ticket_id')->references('id')->on('tickets')->cascadeOnDelete();
            $table->foreignId('servicio_id')->references('id')->on('servicios')->cascadeOnDelete();
            $table->foreignId('user_id')->references('id')->on('users')->nullable()->default(null);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('cprice', 10, 2)->default(0);
            $table->integer('quantity')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_servicio');
    }
};
