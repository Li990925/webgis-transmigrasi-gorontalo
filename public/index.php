<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Enable temporary error display for shared hosting diagnostic
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Verify PHP Version
if (version_compare(PHP_VERSION, '8.3.0', '<')) {
    exit('<div style="font-family:sans-serif;padding:30px;background:#fef2f2;color:#991b1b;border:1px solid #f87171;border-radius:10px;margin:30px auto;max-width:600px;">'
        .'<h2 style="margin-top:0;">Versi PHP Hosting Belum Sesuai</h2>'
        .'<p>Server saat ini menjalankan <strong>PHP '.PHP_VERSION.'</strong>. Laravel membutuhkan minimal <strong>PHP 8.3</strong>.</p>'
        .'<p><strong>Solusi:</strong> Masuk ke Control Panel InfinityFree &rarr; cari menu <em>Select PHP Version</em> &rarr; ubah ke <strong>PHP 8.3</strong> lalu simpan.</p>'
        .'</div>');
}

// Detect application core path inside htdocs (avoids open_basedir restrictions on InfinityFree)
if (file_exists(__DIR__.'/core/bootstrap/app.php')) {
    $corePath = __DIR__.'/core';
} elseif (file_exists(__DIR__.'/bootstrap/app.php')) {
    $corePath = __DIR__;
} else {
    $corePath = __DIR__.'/core';
}

if (! file_exists($corePath.'/vendor/autoload.php')) {
    exit('<div style="font-family:sans-serif;padding:30px;background:#fef2f2;color:#991b1b;border:1px solid #f87171;border-radius:10px;margin:30px auto;max-width:600px;">'
        .'<h2 style="margin-top:0;">File Autoload Vendor Belum Ditemukan</h2>'
        .'<p>Folder core terdeteksi di: <code>'.$corePath.'</code> tetapi file <code>vendor/autoload.php</code> belum ada di lokasi tersebut.</p>'
        .'</div>');
}

// Auto-generate .env if not exists to avoid 500 MissingAppKeyException
if (! file_exists($corePath.'/.env')) {
    $defaultEnv = "APP_NAME=\"WebGIS Transmigrasi Gorontalo\"\n"
        ."APP_ENV=production\n"
        ."APP_KEY=base64:fH7Nq5F8qYhR32eU2X5Vq8pL8G3m9V2wN1z0kX4y8wE=\n"
        ."APP_DEBUG=true\n"
        ."APP_URL=http://transmigrasi-gorontalo.gt.tc\n\n"
        ."APP_LOCALE=id\n"
        ."APP_FALLBACK_LOCALE=id\n\n"
        ."LOG_CHANNEL=stack\n"
        ."LOG_STACK=single\n"
        ."LOG_LEVEL=error\n\n"
        ."DB_CONNECTION=mysql\n"
        ."DB_HOST=sql211.infinityfree.com\n"
        ."DB_PORT=3306\n"
        ."DB_DATABASE=if0_42378255_transmigrasi\n"
        ."DB_USERNAME=if0_42378255\n"
        ."DB_PASSWORD=\n\n"
        ."SESSION_DRIVER=file\n"
        ."SESSION_LIFETIME=120\n"
        ."CACHE_STORE=file\n"
        ."QUEUE_CONNECTION=sync\n"
        ."FILESYSTEM_DISK=local\n";
    @file_put_contents($corePath.'/.env', $defaultEnv);
}

if (empty($_ENV['APP_KEY'])) {
    $_ENV['APP_KEY'] = 'base64:fH7Nq5F8qYhR32eU2X5Vq8pL8G3m9V2wN1z0kX4y8wE=';
    putenv('APP_KEY=base64:fH7Nq5F8qYhR32eU2X5Vq8pL8G3m9V2wN1z0kX4y8wE=');
}

// Ensure essential storage folders exist
@mkdir($corePath.'/storage/framework/cache/data', 0755, true);
@mkdir($corePath.'/storage/framework/sessions', 0755, true);
@mkdir($corePath.'/storage/framework/views', 0755, true);
@mkdir($corePath.'/storage/logs', 0755, true);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $corePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $corePath.'/vendor/autoload.php';

try {
    // Bootstrap Laravel and handle the request...
    /** @var Application $app */
    $app = require_once $corePath.'/bootstrap/app.php';

    $app->usePublicPath(__DIR__);

    $app->handleRequest(Request::capture());
} catch (Throwable $e) {
    echo '<div style="font-family:sans-serif;padding:30px;background:#fff1f2;color:#881337;border:1px solid #fda4af;border-radius:12px;margin:30px auto;max-width:800px;box-shadow:0 10px 25px rgba(0,0,0,0.1);">';
    echo '<h2 style="color:#e11d48;margin-top:0;">Laravel Startup Exception</h2>';
    echo '<p style="font-size:16px;"><strong>Error:</strong> '.htmlspecialchars($e->getMessage()).'</p>';
    echo '<p style="color:#64748b;font-size:13px;"><strong>Lokasi:</strong> '.htmlspecialchars($e->getFile()).' pada baris '.$e->getLine().'</p>';
    echo '<hr style="border:none;border-top:1px solid #fecdd3;margin:20px 0;">';
    echo '<details><summary style="cursor:pointer;font-weight:600;">Lihat Rincian Stack Trace</summary>';
    echo '<pre style="background:#1e293b;color:#f8fafc;padding:15px;border-radius:8px;font-size:12px;overflow-x:auto;margin-top:10px;">'.htmlspecialchars($e->getTraceAsString()).'</pre>';
    echo '</details>';
    echo '</div>';
}
