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
        Schema::table('customer_service_routes', function (Blueprint $table) {
            $table->foreignId('joint_venture_id')->nullable()->after('customer_id')->constrained('customer_joint_ventures')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_service_routes', function (Blueprint $table) {
            $table->dropForeign(['joint_venture_id']);
            $table->dropColumn('joint_venture_id');
        });
    }
};
