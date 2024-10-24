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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->unique()->nullable();
            $table->string('position_id')->unique()->nullable();
            $table->string('exchange')->nullable();
            $table->string('exchange_order_id')->nullable();
            $table->string('symbol');
            $table->string('coin_name');
            $table->string('leverage')->nullable();
            $table->string('side');
            $table->string('type');
            $table->string('status');
            $table->string('price');
            $table->string('balance')->nullable();
            $table->string('tp')->nullable();
            $table->string('sl')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
