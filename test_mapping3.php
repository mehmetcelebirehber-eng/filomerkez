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
$xmlString = $s->getLicensePlateNodeMappings();
if (!$xmlString) {
    echo "No XML returned\n";
    exit;
}

$mapping = [];
$xmlString = preg_replace('/(<\/?)(diffgr:|msdata:|xs:)/i', '$1', $xmlString);
$xmlString = preg_replace('/\s+(diffgr:|msdata:|xs:)[a-zA-Z0-9]+="[^"]*"/i', '', $xmlString);
$xml = simplexml_load_string('<root>' . $xmlString . '</root>');
if ($xml) {
    $rows = $xml->xpath('//tblPlaka');
    foreach ($rows as $row) {
        $node = (string)$row->Cihaz_x0020_No;
        $plaka = (string)$row->Plaka;
        if ($node && $plaka) {
            $mapping[$node] = trim($plaka);
        }
    }
}
echo "XML length: " . strlen($xmlString) . "\n";
echo "First 500 chars: " . substr($xmlString, 0, 500) . "\n";
print_r($mapping);
