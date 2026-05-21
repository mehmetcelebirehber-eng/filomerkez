<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$url = "http://ws.arvento.com/v1/report.asmx";

function callArvento($method, $params) {
    global $url;
    $xmlParams = "";
    foreach ($params as $key => $value) {
        $xmlParams .= "<{$key}>{$value}</{$key}>";
    }

    $envelope = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <' . $method . ' xmlns="http://www.arvento.com/">
      ' . $xmlParams . '
    </' . $method . '>
  </soap:Body>
</soap:Envelope>';

    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Content-Type' => 'text/xml; charset=utf-8',
        'SOAPAction'   => 'http://www.arvento.com/' . $method,
    ])->withBody($envelope, 'text/xml')->post($url);

    return $response->body();
}

$credentials = [
    'Username' => 'mehmettasimacilik',
    'PIN1' => 'Mehmet411.',
    'PIN2' => 'Mehmet411.'
];

echo "Testing GetVehicleStatusJSON...\n";
$resJson = callArvento('GetVehicleStatusJSON', array_merge($credentials, ['Language' => '0']));
echo "JSON length: " . strlen($resJson) . "\n";
file_put_contents('storage/logs/arvento_json.txt', $resJson);

echo "Testing GetVehicleStatus (XML)...\n";
$resXml = callArvento('GetVehicleStatus', array_merge($credentials, ['Language' => '0']));
echo "XML length: " . strlen($resXml) . "\n";
file_put_contents('storage/logs/arvento_xml.txt', $resXml);

echo "Done.\n";
