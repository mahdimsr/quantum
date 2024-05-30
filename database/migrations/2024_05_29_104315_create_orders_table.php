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
            $table->string('order_id');
            $table->string('market');
            $table->string('market_type');
            $table->string('side');
            $table->string('type');
            $table->string('amount');
            $table->string('current_price');
            $table->string('price');
            $table->string('stop_loss_price');
            $table->string('take_profit_price');
            $table->boolean('has_stop_loss');
            $table->boolean('has_take_profit');
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
