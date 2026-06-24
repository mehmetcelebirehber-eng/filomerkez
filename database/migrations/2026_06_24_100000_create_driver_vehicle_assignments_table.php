<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Araç zimmet geçmişi tablosu.
     * Şoförün hangi tarihte hangi araca atandığını ve ne zaman ayrıldığını tutar.
     * Bu sayede puantaj ve maaş hesaplamaları geriye dönük doğru çalışır.
     */
    public function up(): void
    {
        Schema::create('driver_vehicle_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->date('assigned_at');                              // Zimmet başlangıç tarihi
            $table->string('assigned_shift')->default('morning');     // morning, evening, full_day
            $table->date('unassigned_at')->nullable();                // Zimmet bitiş tarihi (null = hala atanmış)
            $table->string('unassigned_shift')->nullable();           // morning, evening, full_day
            $table->text('notes')->nullable();
            $table->timestamps();

            // Bir şoförün aynı araçta aynı tarihte birden fazla aktif kaydı olmamalı
            $table->index(['driver_id', 'vehicle_id', 'assigned_at'], 'idx_dva_driver_vehicle_date');
            $table->index(['vehicle_id', 'assigned_at', 'unassigned_at'], 'idx_dva_vehicle_dates');
            $table->index(['driver_id', 'assigned_at', 'unassigned_at'], 'idx_dva_driver_dates');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_vehicle_assignments');
    }
};
