<?php
session_start();

// Cek login (misal pakai session 'user_id')
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
use UserService\Services\SoundServiceClient;

$soundClient = new SoundServiceClient();
$soundData = $soundClient->getAllSoundData();
$current = $soundData['currentSoundLevel'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sound Level Ruangan A</title>
</head>
<body>
    <h1>Sound Level Ruangan A</h1>
    <?php if ($current): ?>
        <p><strong>Amplitude:</strong> <?= htmlspecialchars($current['amplitude']) ?></p>
        <p><strong>Is Loud:</strong> <?= $current['isLoud'] ? 'Yes' : 'No' ?></p>
    <?php else: ?>
        <p>Data tidak tersedia.</p>
    <?php endif; ?>
    <a href="/logout.php">Logout</a>
</body>
</html>
