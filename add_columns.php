<?php
require_once __DIR__ . '/vendor/autoload.php';

// Load environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Add columns if they don't exist
    $columnsExist = true;
    
    // Check if columns exist by getting column information
    $columns = DB::select("DESCRIBE doc_pending");
    $columnNames = array_column(json_decode(json_encode($columns), true), 'Field');
    
    $missingColumns = [];
    if (!in_array('temp_nested_related_docs', $columnNames)) {
        $missingColumns[] = 'temp_nested_related_docs';
    }
    if (!in_array('temp_relationship_types', $columnNames)) {
        $missingColumns[] = 'temp_relationship_types';
    }
    if (!in_array('temp_relationship_notes', $columnNames)) {
        $missingColumns[] = 'temp_relationship_notes';
    }
    
    if (!empty($missingColumns)) {
        foreach ($missingColumns as $column) {
            if ($column === 'temp_nested_related_docs') {
                DB::statement("ALTER TABLE doc_pending ADD COLUMN temp_nested_related_docs TEXT NULL");
            } elseif ($column === 'temp_relationship_types') {
                DB::statement("ALTER TABLE doc_pending ADD COLUMN temp_relationship_types TEXT NULL");
            } elseif ($column === 'temp_relationship_notes') {
                DB::statement("ALTER TABLE doc_pending ADD COLUMN temp_relationship_notes TEXT NULL");
            }
        }
        echo "Added missing columns: " . implode(', ', $missingColumns) . "\n";
    } else {
        echo "All columns already exist.\n";
    }
    
    // Mark our migration as run by inserting into migrations table
    $migrationExists = DB::table('migrations')
        ->where('migration', '2026_01_30_104916_add_temp_nested_related_docs_to_doc_pending_table')
        ->exists();
    
    if (!$migrationExists) {
        DB::table('migrations')->insert([
            'migration' => '2026_01_30_104916_add_temp_nested_related_docs_to_doc_pending_table',
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        echo "Marked migration as run.\n";
    } else {
        echo "Migration already marked as run.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}