<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$company = App\Models\Company::create(['name' => 'Test Turizm', 'status' => 'active']);
$user = App\Models\User::create([
    'name' => 'Firma Yoneticisi', 
    'email' => 'test@test.com', 
    'password' => Illuminate\Support\Facades\Hash::make('12345678'), 
    'role' => 'company_admin', 
    'company_id' => $company->id, 
    'is_active' => true
]);

echo "TEST FIRMASI VE KULLANICISI OLUSTURULDU:\n";
echo "E-posta: test@test.com\n";
echo "Şifre: 12345678\n";
