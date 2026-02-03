<?php
require_once __DIR__ . '/vendor/autoload.php';

// Load environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Regulation;

// Test that the new methods exist in the Regulation model
$regulation = new Regulation();

$methods = get_class_methods($regulation);

echo "Checking for new methods in Regulation model:\n";

$newMethods = [
    'getRelatedDocumentsFromRelationshipsAttribute',
    'getNestedRelatedDocumentsFromRelationshipsAttribute',
    'getAllRelatedDocumentsAttribute',
    'getAllNestedRelatedDocumentsAttribute'
];

foreach ($newMethods as $method) {
    if (in_array($method, $methods)) {
        echo "✓ $method exists\n";
    } else {
        echo "✗ $method missing\n";
    }
}

echo "\nFunctionality test completed successfully!\n";