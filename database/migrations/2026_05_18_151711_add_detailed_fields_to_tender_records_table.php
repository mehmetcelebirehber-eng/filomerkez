<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tender_records', function (Blueprint $table) {
            $table->integer('total_vehicles')->nullable()->after('duration_days');
            $table->integer('minibus_count')->nullable()->after('total_vehicles');
            $table->integer('midibus_count')->nullable()->after('minibus_count');
            $table->integer('bus_count')->nullable()->after('midibus_count');
            $table->integer('taxi_count')->nullable()->after('bus_count');
            $table->string('vehicle_model_requirement')->nullable()->after('taxi_count');
            
            $table->decimal('winning_unit_price', 15, 2)->nullable()->after('winning_amount');
            $table->json('bids')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('tender_records', function (Blueprint $table) {
            $table->dropColumn([
                'total_vehicles',
                'minibus_count',
                'midibus_count',
                'bus_count',
                'taxi_count',
                'vehicle_model_requirement',
                'winning_unit_price',
                'bids'
            ]);
        });
    }
};
