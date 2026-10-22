<?php
// Setup Helper untuk WebGIS Transmigrasi Gorontalo di Shared Hosting
$coreDir = null;
if (file_exists(__DIR__ . '/../core')) {
    $coreDir = realpath(__DIR__ . '/../core');
} elseif (file_exists(__DIR__ . '/../../core')) {
    $coreDir = realpath(__DIR__ . '/../../core');
}

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['db_password'] ?? '';
    if (!empty($password) && $coreDir) {
        $env = "APP_NAME=\"WebGIS Transmigrasi Gorontalo\"\n"
            . "APP_ENV=production\n"
            . "APP_KEY=base64:fH7Nq5F8qYhR32eU2X5Vq8pL8G3m9V2wN1z0kX4y8wE=\n"
            . "APP_DEBUG=false\n"
            . "APP_URL=http://transmigrasi-gorontalo.gt.tc\n\n"
            . "APP_LOCALE=id\n"
            . "APP_FALLBACK_LOCALE=id\n\n"
            . "LOG_CHANNEL=stack\n"
            . "LOG_STACK=single\n"
            . "LOG_LEVEL=error\n\n"
            . "DB_CONNECTION=mysql\n"
            . "DB_HOST=sql211.infinityfree.com\n"
            . "DB_PORT=3306\n"
            . "DB_DATABASE=if0_42378255_transmigrasi\n"
            . "DB_USERNAME=if0_42378255\n"
            . "DB_PASSWORD=\"{$password}\"\n\n"
            . "SESSION_DRIVER=file\n"
            . "SESSION_LIFETIME=120\n"
            . "CACHE_STORE=file\n"
            . "QUEUE_CONNECTION=sync\n"
            . "FILESYSTEM_DISK=local\n";

        if (file_put_contents($coreDir . '/.env', $env)) {
            $status = 'success';
            $message = 'File .env berhasil dibuat dan dikonfigurasi secara otomatis!';
        } else {
            $status = 'error';
            $message = 'Gagal menulis file .env ke folder core. Periksa izin folder core.';
        }
    } else {
        $status = 'error';
        $message = 'Password hosting tidak boleh kosong.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi WebGIS Transmigrasi Gorontalo</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #081e36; color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; }
        .card { background: #0d2a4a; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 32px; max-width: 480px; width: 100%; box-shadow: 0 20px 40px rgba(0,0,0,0.4); text-align: center; }
        h1 { font-size: 20px; margin-bottom: 8px; color: #facc15; }
        p { color: #94a3b8; font-size: 14px; margin-bottom: 24px; line-height: 1.5; }
        .input-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #cbd5e1; }
        input[type="password"] { width: 100%; padding: 12px 14px; background: #081e36; border: 1px solid #1e3a5f; border-radius: 8px; color: #fff; font-size: 14px; box-sizing: border-box; }
        input[type="password"]:focus { outline: none; border-color: #1a73e8; }
        button { width: 100%; padding: 14px; background: #1a73e8; color: #fff; border: none; border-radius: 8px; font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.2s; }
        button:hover { background: #1557b0; }
        .alert-success { background: rgba(34, 197, 94, 0.2); border: 1px solid #22c55e; color: #4ade80; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #f87171; padding: 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .btn-home { display: inline-block; margin-top: 14px; color: #fff; background: #10b981; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>⚙️ Aktivasi WebGIS Transmigrasi</h1>
        <p>Masukkan password akun hosting InfinityFree Anda untuk menghubungkan database ke sistem WebGIS secara otomatis.</p>

        <?php if ($status === 'success'): ?>
            <div class="alert-success">
                <strong>Berhasil!</strong> <?= htmlspecialchars($message) ?><br><br>
                <a href="/" class="btn-home">Buka WebGIS Sekarang &rarr;</a>
            </div>
        <?php else: ?>
            <?php if ($status === 'error'): ?>
                <div class="alert-error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Password Akun InfinityFree (vPanel):</label>
                    <input type="password" name="db_password" placeholder="Ketik password hosting Anda..." required autofocus>
                </div>
                <button type="submit">Aktifkan & Hubungkan Database</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
