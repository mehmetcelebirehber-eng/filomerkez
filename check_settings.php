<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$setting = App\Models\VehicleTrackingSetting::where('provider', 'arvento')->first();
if ($setting) {
    print_r($setting->toArray());
} else {
    echo "No setting found.\n";
}
