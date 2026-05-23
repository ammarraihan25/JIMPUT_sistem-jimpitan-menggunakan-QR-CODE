<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e) {
            if (env('APP_DEBUG') === 'true') {
                header("HTTP/1.1 500 Internal Server Error");
                echo "<html><head><title>Laravel Vercel Debugger - Primary Exception</title></head><body style='font-family: sans-serif; padding: 30px; background: #fff5f5; color: #9b2c2c;'>";
                echo "<h1 style='border-bottom: 2px solid #feb2b2; padding-bottom: 10px;'>Fatal Boot Error on Vercel (Primary Exception)</h1>";
                echo "<p><strong>Exception Class:</strong> " . get_class($e) . "</p>";
                echo "<p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
                echo "<h3>Stack Trace:</h3>";
                echo "<pre style='background: #fff; padding: 15px; border: 1px solid #fed7d7; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 13px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                echo "</body></html>";
                exit;
            }
        });
    })->create();
