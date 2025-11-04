<?php
// Quick debug script to check session timeout values
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SessionSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Session Timeout Debug ===\n";

// Check if table exists
echo "Table exists: " . (Schema::hasTable('session_settings') ? 'YES' : 'NO') . "\n";

// Get raw database value
try {
    $rawData = DB::table('session_settings')->first();
    echo "Raw DB data: " . json_encode($rawData) . "\n";
    
    // Get via model
    $modelTimeout = SessionSetting::getCurrentTimeout();
    echo "Model timeout: " . $modelTimeout . " minutes\n";
    
    // Get config value
    $configTimeout = config('session.lifetime');
    echo "Config timeout: " . $configTimeout . " minutes\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "=== End Debug ===\n";
?>
