<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$docs = \App\Models\Document::orderBy('end_date', 'desc')->get();
$seen = [];
$archivedCount = 0;

foreach ($docs as $doc) {
    $key = $doc->documentable_type . '_' . $doc->documentable_id . '_' . $doc->document_type;
    
    if (isset($seen[$key])) {
        // We have already seen a newer document for this type, so this one is old/duplicate
        if (is_null($doc->archived_at)) {
            $doc->archived_at = now();
            $doc->save();
            $archivedCount++;
        }
    } else {
        // This is the newest document for this type
        $seen[$key] = true;
    }
}

echo "Archived " . $archivedCount . " duplicate documents.\n";
