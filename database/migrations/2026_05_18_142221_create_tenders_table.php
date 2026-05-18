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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            
            $table->string('institution_name'); // e.g. Devlet Su İşleri
            $table->date('tender_date'); // e.g. 2025-05-18
            $table->string('tender_registration_number')->nullable(); // İhale Kayıt No (İKN)
            
            $table->text('vehicle_details')->nullable(); // e.g. 2 Minibüs, 1 Otobüs
            $table->integer('duration_days')->nullable(); // e.g. 180 gün
            
            $table->decimal('approximate_cost', 15, 2)->nullable(); // Yaklaşık maliyet
            $table->decimal('our_bid', 15, 2)->nullable(); // Bizim teklifimiz
            
            $table->string('winning_company')->nullable(); // İhaleyi kazanan firma
            $table->decimal('winning_amount', 15, 2)->nullable(); // Kazanan tutar
            
            $table->string('status')->default('Değerlendirmede'); // Kazanıldı, Kaybedildi, Değerlendirmede, İptal
            $table->string('document_path')->nullable(); // PDF dosyası
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
