<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Check if column exists
    $columns = DB::select("SHOW COLUMNS FROM regulations LIKE 'nested_related_docs'");
    
    if (!empty($columns)) {
        echo "Column 'nested_related_docs' exists in regulations table\n";
        
        // Test accessing a record
        $reg = \App\Models\Regulation::first();
        if ($reg) {
            echo "First regulation record:\n";
            echo "ID: " . $reg->id . "\n";
            echo "Title: " . $reg->title . "\n";
            echo "Nested related docs column value: " . ($reg->nested_related_docs_column ?? 'NULL') . "\n";
        } else {
            echo "No regulations found\n";
        }
    } else {
        echo "Column 'nested_related_docs' does NOT exist in regulations table\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}