<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_route_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_service_route_id')->constrained()->cascadeOnDelete();
            $table->string('stop_name');
            $table->time('stop_time')->nullable();
            $table->integer('stop_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_route_stops');
    }
};
