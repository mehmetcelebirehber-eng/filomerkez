<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'test@test.com')->first();
if ($user) {
    // Modelde mutator olduğu için Hash::make() yapmadan direkt veriyoruz.
    $user->update(['password' => '12345678']);
    echo "Sifre basariyla duzeltildi.\n";
} else {
    echo "Kullanici bulunamadi.\n";
}
