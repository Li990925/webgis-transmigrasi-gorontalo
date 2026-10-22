<?php
// Setup Helper untuk WebGIS Transmigrasi Gorontalo di Shared Hosting (InfinityFree)
ini_set('display_errors', '1');
error_reporting(E_ALL);

$coreDir = null;
if (file_exists(__DIR__.'/core/bootstrap/app.php')) {
    $coreDir = __DIR__.'/core';
} elseif (file_exists(__DIR__.'/bootstrap/app.php')) {
    $coreDir = __DIR__;
} else {
    $coreDir = __DIR__.'/core';
}

$message = '';
$status = '';
$tableCount = 0;
$dbConnected = false;

// Default values for InfinityFree account
$defaultHost = 'sql211.infinityfree.com';
$defaultDatabase = 'if0_42378255_transmigrasi';
$defaultUser = 'if0_42378255';
$defaultAppUrl = 'http://transmigrasi-gorontalo.gt.tc';

// Check if current .env exists and read it
$currentEnv = '';
if (file_exists($coreDir.'/.env')) {
    $currentEnv = file_get_contents($coreDir.'/.env');
} elseif (file_exists(__DIR__.'/.env')) {
    $currentEnv = file_get_contents(__DIR__.'/.env');
}

if (! empty($currentEnv)) {
    if (preg_match('/DB_HOST=(.*)/', $currentEnv, $m)) {
        $defaultHost = trim($m[1], "\"'\r\n ");
    }
    if (preg_match('/DB_DATABASE=(.*)/', $currentEnv, $m)) {
        $defaultDatabase = trim($m[1], "\"'\r\n ");
    }
    if (preg_match('/DB_USERNAME=(.*)/', $currentEnv, $m)) {
        $defaultUser = trim($m[1], "\"'\r\n ");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save_config';
    $dbHost = trim($_POST['db_host'] ?? $defaultHost);
    $dbName = trim($_POST['db_database'] ?? $defaultDatabase);
    $dbUser = trim($_POST['db_username'] ?? $defaultUser);
    $dbPass = trim($_POST['db_password'] ?? '');

    // 1. Test PDO MySQL connection first
    try {
        $pdo = new PDO("mysql:host={$dbHost};port=3306;dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]);
        $dbConnected = true;

        // Count existing tables
        $stmt = $pdo->query('SHOW TABLES');
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $tableCount = count($tables);

        // If action is import database or tables are empty
        if ($action === 'import' || $tableCount === 0) {
            $sqlFile = null;
            $candidates = [
                __DIR__.'/database/infinityfree_transmigrasi.sql',
                $coreDir.'/database/infinityfree_transmigrasi.sql',
                __DIR__.'/core/database/infinityfree_transmigrasi.sql',
            ];
            foreach ($candidates as $cand) {
                if (file_exists($cand)) {
                    $sqlFile = $cand;
                    break;
                }
            }

            if ($sqlFile) {
                $sqlContent = file_get_contents($sqlFile);
                $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
                $pdo->exec($sqlContent);
                $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');

                // recount tables
                $stmt = $pdo->query('SHOW TABLES');
                $tableCount = count($stmt->fetchAll(PDO::FETCH_COLUMN));
            }
        }

        // 2. Write correct .env
        $env = "APP_NAME=\"WebGIS Transmigrasi Gorontalo\"\n"
            ."APP_ENV=production\n"
            ."APP_KEY=base64:fH7Nq5F8qYhR32eU2X5Vq8pL8G3m9V2wN1z0kX4y8wE=\n"
            ."APP_DEBUG=true\n"
            ."APP_URL={$defaultAppUrl}\n\n"
            ."APP_LOCALE=id\n"
            ."APP_FALLBACK_LOCALE=id\n\n"
            ."LOG_CHANNEL=stack\n"
            ."LOG_STACK=single\n"
            ."LOG_LEVEL=error\n\n"
            ."DB_CONNECTION=mysql\n"
            ."DB_HOST={$dbHost}\n"
            ."DB_PORT=3306\n"
            ."DB_DATABASE={$dbName}\n"
            ."DB_USERNAME={$dbUser}\n"
            ."DB_PASSWORD=\"{$dbPass}\"\n\n"
            ."SESSION_DRIVER=file\n"
            ."SESSION_LIFETIME=120\n"
            ."CACHE_STORE=file\n"
            ."QUEUE_CONNECTION=sync\n"
            ."FILESYSTEM_DISK=local\n";

        // Save to coreDir and root __DIR__
        @file_put_contents($coreDir.'/.env', $env);
        @file_put_contents(__DIR__.'/.env', $env);

        $status = 'success';
        $message = "Koneksi MySQL Sukses! File .env berhasil diperbarui ({$tableCount} tabel terdeteksi di database).";

    } catch (PDOException $e) {
        $status = 'error';
        $message = 'Gagal terhubung ke MySQL: '.$e->getMessage().'. Pastikan Nama Host, Database, User, dan Password vPanel Anda sudah benar.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Database WebGIS Transmigrasi</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #081e36; color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; }
        .card { background: #0d2a4a; border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 32px; max-width: 540px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
        h1 { font-size: 20px; margin-top: 0; margin-bottom: 8px; color: #facc15; text-align: center; }
        p.subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 24px; text-align: center; line-height: 1.5; }
        .input-group { text-align: left; margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #cbd5e1; }
        input[type="text"], input[type="password"] { width: 100%; padding: 11px 13px; background: #081e36; border: 1px solid #1e3a5f; border-radius: 8px; color: #fff; font-size: 14px; box-sizing: border-box; }
        input:focus { outline: none; border-color: #1a73e8; box-shadow: 0 0 0 2px rgba(26,115,232,0.3); }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        button { width: 100%; padding: 14px; background: #1a73e8; color: #fff; border: none; border-radius: 8px; font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.2s; margin-top: 8px; }
        button:hover { background: #1557b0; }
        .alert-success { background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; color: #4ade80; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; line-height: 1.5; }
        .alert-error { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #f87171; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; line-height: 1.5; }
        .btn-home { display: block; width: 100%; box-sizing: border-box; text-align: center; margin-top: 14px; color: #fff; background: #10b981; padding: 13px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 15px; }
        .btn-home:hover { background: #059669; }
        .btn-import { background: #d97706; margin-top: 10px; }
        .btn-import:hover { background: #b45309; }
        .badge { display: inline-block; padding: 4px 10px; background: #1e3a5f; border-radius: 6px; font-size: 12px; color: #93c5fd; font-family: monospace; }
    </style>
</head>
<body>
    <div class="card">
        <h1>⚙️ Konfigurasi Database WebGIS</h1>
        <p class="subtitle">Hubungkan MySQL Hosting InfinityFree Anda agar WebGIS Transmigrasi langsung aktif online.</p>

        <?php if ($status === 'success') { ?>
            <div class="alert-success">
                <strong>✅ Berhasil!</strong><br>
                <?= htmlspecialchars($message) ?><br><br>
                Status Tabel: <strong><?= $tableCount ?> tabel aktif</strong> di database.<br>
                <a href="/" class="btn-home">Buka WebGIS Sekarang &rarr;</a>
                <?php if ($tableCount < 4) { ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="import">
                        <input type="hidden" name="db_host" value="<?= htmlspecialchars($dbHost) ?>">
                        <input type="hidden" name="db_database" value="<?= htmlspecialchars($dbName) ?>">
                        <input type="hidden" name="db_username" value="<?= htmlspecialchars($dbUser) ?>">
                        <input type="hidden" name="db_password" value="<?= htmlspecialchars($dbPass) ?>">
                        <button type="submit" class="btn-import">📥 Klik untuk Import Data & Tabel WebGIS (Otomatis)</button>
                    </form>
                <?php } ?>
            </div>
        <?php } else { ?>
            <?php if ($status === 'error') { ?>
                <div class="alert-error">
                    <strong>❌ Terjadi Kesalahan:</strong><br>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php } ?>

            <form method="POST">
                <input type="hidden" name="action" value="save_config">
                <div class="input-group">
                    <label>MySQL Host Name:</label>
                    <input type="text" name="db_host" value="<?= htmlspecialchars($defaultHost) ?>" required>
                </div>
                <div class="grid-2">
                    <div class="input-group">
                        <label>Nama Database:</label>
                        <input type="text" name="db_database" value="<?= htmlspecialchars($defaultDatabase) ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Username MySQL:</label>
                        <input type="text" name="db_username" value="<?= htmlspecialchars($defaultUser) ?>" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Password Akun InfinityFree (vPanel Password):</label>
                    <input type="password" name="db_password" placeholder="Masukkan password vPanel Anda..." required autofocus>
                </div>
                <button type="submit">Hubungkan & Aktifkan WebGIS</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
