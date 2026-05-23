<?php

// 1. Workaround for Vercel's read-only filesystem
// We set the Laravel storage path to /tmp/storage and create required subdirectories
$storagePath = '/tmp/storage';
$directories = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

// 2. Set dynamic environment variables for Serverless
putenv("LARAVEL_STORAGE_PATH={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

// 3. Optimize configurations for serverless environment
putenv("SESSION_DRIVER=cookie"); // Use cookies for stateless serverless sessions
putenv("LOG_CHANNEL=stderr");    // Send logs to Vercel's logs panel
putenv("CACHE_STORE=array");     // Use array cache (or file/database if needed)

// 4. Temporarily enable debug mode to show exact error if DB/App Key is misconfigured
putenv("APP_DEBUG=true");

// Forward request to the Laravel front controller
require __DIR__ . '/../public/index.php';
