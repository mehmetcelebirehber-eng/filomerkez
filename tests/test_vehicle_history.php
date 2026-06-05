<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$vehicle = \App\Models\Fleet\Vehicle::where('plate', '42 C 0051')->first();
if (!$vehicle) {
    echo "Vehicle not found\n";
    exit;
}

$trips = \App\Models\Trip::where(function($q) use($vehicle) {
    $q->where('vehicle_id', $vehicle->id)
      ->orWhere('morning_vehicle_id', $vehicle->id)
      ->orWhere('evening_vehicle_id', $vehicle->id);
})->get(['id', 'trip_date', 'driver_id', 'morning_driver_id', 'evening_driver_id', 'vehicle_id', 'morning_vehicle_id', 'evening_vehicle_id']);

echo "Total trips: " . $trips->count() . "\n";
foreach($trips as $t) {
    echo "Trip ID: {$t->id} | Date: {$t->trip_date} | Driver: {$t->driver_id} | M_Driver: {$t->morning_driver_id} | E_Driver: {$t->evening_driver_id} | Veh: {$t->vehicle_id} | M_Veh: {$t->morning_vehicle_id} | E_Veh: {$t->evening_vehicle_id}\n";
}
