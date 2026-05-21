<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$s = new \App\Services\ArventoService(new \App\Models\VehicleTrackingSetting([
    'username' => 'mehmettasimacilik', 
    'app_id' => 'Mehmet411.', 
    'app_key' => 'Mehmet411.'
])); 
echo $s->getLicensePlateNodeMappings();
