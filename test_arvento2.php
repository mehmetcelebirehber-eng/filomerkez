<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = new \App\Services\ArventoService(new \App\Models\VehicleTrackingSetting([
    'username' => 'mehmettasimacilik', 
    'app_id' => 'Mehmet411.', 
    'app_key' => 'Mehmet411.'
])); 
$data = $s->getVehicleStatus();
echo "Found " . count($data) . " vehicles\n";
if (count($data) > 0) {
    print_r($data[0]);
}
