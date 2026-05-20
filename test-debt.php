<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

$request = Illuminate\Http\Request::create('/fuel-stations/calculate-debt?fuel_station_id=1&start_date=2026-01-01&end_date=2026-05-15', 'GET');
$response = $kernel->handle($request);

echo $response->getContent();
