<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$s = new \App\Services\ArventoService(new \App\Models\VehicleTrackingSetting([
    'username' => 'mehmettasimacilik', 
    'app_id' => 'Mehmet411.', 
    'app_key' => 'Mehmet411.',
    'company_id' => 1
])); 
$plates = $s->getMappedLicensePlates();
print_r($plates);
if (empty($plates)) echo "Plates array is empty!\n";
