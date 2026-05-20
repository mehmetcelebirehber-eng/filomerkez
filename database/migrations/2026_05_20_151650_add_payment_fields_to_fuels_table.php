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
        Schema::table('fuels', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('total_cost'); // unpaid, partial, paid
            $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuels', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'paid_amount']);
        });
    }
};
