<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$doc33 = \App\Models\Regulation::find(33);
$doc34 = \App\Models\Regulation::find(34);
$doc35 = \App\Models\Regulation::find(35);

echo "ID 33: " . $doc33->title . " (Doc: " . $doc33->id . ")\n";
echo "ID 34: " . $doc34->title . " (Doc: " . $doc34->id . ")\n";
echo "ID 35: " . $doc35->title . " (Doc: " . $doc35->id . ")\n";

// Check if they're actually the same document or different versions
echo "\n--- Document Details ---\n";
echo "33 - Version: " . $doc33->document_version . ", Issue: " . $doc33->issue_date . "\n";
echo "34 - Version: " . $doc34->document_version . ", Issue: " . $doc34->issue_date . "\n";
echo "35 - Version: " . $doc35->document_version . ", Issue: " . $doc35->issue_date . "\n";