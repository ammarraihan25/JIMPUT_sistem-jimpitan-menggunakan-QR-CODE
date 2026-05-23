<?php

// Enable error reporting to catch early bootstrap errors
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

try {
    // 1. Workaround for Vercel's read-only filesystem
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

} catch (\Throwable $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "<html><head><title>Laravel Vercel Debugger</title></head><body style='font-family: sans-serif; padding: 30px; background: #fff5f5; color: #9b2c2c;'>";
    echo "<h1 style='border-bottom: 2px solid #feb2b2; padding-bottom: 10px;'>Fatal Boot Error on Vercel</h1>";
    
    $current = $e;
    $count = 1;
    while ($current) {
        echo "<div style='margin-bottom: 20px; padding: 15px; border: 1px solid #feb2b2; background: #fff; border-radius: 4px;'>";
        echo "<h2>Exception #" . $count . " (" . get_class($current) . ")</h2>";
        echo "<p><strong>Error Message:</strong> " . htmlspecialchars($current->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($current->getFile()) . " (Line " . $current->getLine() . ")</p>";
        echo "<h3>Stack Trace:</h3>";
        echo "<pre style='background: #f7fafc; padding: 15px; border: 1px solid #e2e8f0; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 13px;'>" . htmlspecialchars($current->getTraceAsString()) . "</pre>";
        echo "</div>";
        $current = $current->getPrevious();
        $count++;
    }
    
    echo "</body></html>";
    exit;
}

