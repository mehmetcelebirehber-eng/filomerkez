<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the existing tenders table entirely because it has no useful data yet,
        // and recreate it cleanly as the "Folder" table.
        Schema::dropIfExists('tenders');

        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('institution_name'); // Kurum Adı (örn: Devlet Su İşleri)
            $table->string('vehicle_details')->nullable(); // Araç İhtiyacı Detayı
            $table->timestamps();
            $table->softDeletes();
        });

        // Create the child table for Tender Records (History)
        Schema::create('tender_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_id')->index();
            $table->date('tender_date')->nullable();
            $table->string('tender_registration_number')->nullable(); // İKN
            $table->integer('duration_days')->nullable();
            $table->decimal('approximate_cost', 15, 2)->nullable();
            $table->decimal('our_bid', 15, 2)->nullable();
            $table->string('winning_company')->nullable();
            $table->decimal('winning_amount', 15, 2)->nullable();
            $table->string('status')->default('Değerlendirmede'); // Kazanıldı, Kaybedildi vb.
            $table->string('document_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tender_id')->references('id')->on('tenders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tender_records');
        Schema::dropIfExists('tenders');
    }
};
