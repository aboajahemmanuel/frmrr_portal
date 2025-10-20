<?php
/**
 * Laravel Auto Deployment Script
 * Secure GitHub Webhook Deployment
 */

// $secret = 'YOUR_WEBHOOK_SECRET'; // must match GitHub webhook secret

// // Verify request signature
// $signature = 'sha256=' . hash_hmac('sha256', file_get_contents('php://input'), $secret);
// if (!hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '')) {
//     http_response_code(403);
//     exit('Invalid signature');
// }

// Deployment process
$commands = [
    'cd C:\wamp64\www\fmrr_deploy',
    'git pull origin main',
    //'composer install --no-dev --optimize-autoloader',
    //'php artisan migrate --force',
    'php artisan cache:clear',
    'php artisan config:cache',
    //'php artisan route:cache',
];

$output = '';
foreach ($commands as $cmd) {
    $output .= shell_exec($cmd . ' 2>&1');
}

file_put_contents('deploy-log.txt', $output);
echo "✅ Deployment complete.\n";
