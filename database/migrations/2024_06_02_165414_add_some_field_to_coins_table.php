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
        Schema::table('coins', function (Blueprint $table) {
            $table->integer('leverage')->nullable()->after('percent_tolerance');
            $table->tinyInteger('status')->nullable()->after('leverage');
            $table->string('asset')->nullable()->after('status');
            $table->integer('order')->nullable()->after('asset');
            $table->text('strategies')->nullable()->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->dropColumn('leverage');
            $table->dropColumn('status');
            $table->dropColumn('asset');
            $table->dropColumn('order');
            $table->dropColumn('strategies');
        });
    }
};
