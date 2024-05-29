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
            $table->decimal('amount', 20, 10);
            $table->decimal('current_price', 20, 10);
            $table->decimal('price', 20, 10);
            $table->decimal('stop_loss_price', 20, 10);
            $table->decimal('take_profit_price', 20, 10);
            $table->boolean('has_stop_loss');
            $table->boolean('has_take_profit');
            $table->decimal('unfilled_amount', 20, 10)->nullable();
            $table->decimal('filled_amount', 20, 10)->nullable();
            $table->decimal('filled_value', 20, 10)->nullable();
            $table->string('client_id')->nullable();
            $table->decimal('fee', 20, 10)->nullable();
            $table->string('fee_ccy')->nullable();
            $table->decimal('maker_fee_rate', 20, 10)->nullable();
            $table->decimal('taker_fee_rate', 20, 10)->nullable();
            $table->decimal('last_filled_amount', 20, 10)->nullable();
            $table->decimal('last_filled_price', 20, 10)->nullable();
            $table->decimal('realized_pnl', 20, 10)->nullable();
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
