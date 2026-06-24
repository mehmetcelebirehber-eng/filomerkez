<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::first();
auth()->login($user);

$request = \Illuminate\Http\Request::create('/api/v1/vehicle-tracking/live', 'GET');
$request->setUserResolver(function() use ($user) { return $user; });

try {
    $controller = new \App\Http\Controllers\Api\V1\VehicleTrackingApiController();
    $response = $controller->live($request);
    echo $response->getContent();
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
