<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$response = $kernel->handle(
    $request = Request::capture()
);

try {
    echo "<h1>Sistem Guncelleme Araci</h1>";
    
    echo "<li>Git üzerinden güncel kodlar çekiliyor...</li>";
    $gitOutput = shell_exec('git pull origin main 2>&1');
    echo "<pre style='background:#f4f4f4; padding:10px; font-size:12px;'>" . htmlspecialchars($gitOutput) . "</pre>";
    
    echo "<li>Onbellek temizleniyor...</li>";
    Artisan::call('optimize:clear');
    echo "<li>Optimize clear calistirildi.</li>";
    
    echo "<li>Veritabani tablolari guncelleniyor...</li>";
    Artisan::call('migrate', ['--force' => true]);
    echo "<li>Migrate basarili.</li>";

    echo "<li>Yetkiler ayarlaniyor...</li>";
    Artisan::call('db:seed', ['--class' => 'TendersPermissionSeeder', '--force' => true]);
    echo "<li>Yetkiler (Seeder) eklendi.</li>";

    echo "<h2>TUM ISLEMLER BASARIYLA TAMAMLANDI! 🎉</h2>";
    echo "<p>Artik <a href='/app'>sisteme giris</a> yapabilirsiniz.</p>";

} catch (\Exception $e) {
    echo "<h2>HATA OLUSTU:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
