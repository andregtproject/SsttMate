<?php
session_start();

// Cek login (misal pakai session 'user_id')
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';
use UserService\Services\SoundServiceClient;

try {
    $soundClient = new SoundServiceClient();
    $soundResponse = $soundClient->getAllSoundData();
    $error = null;
} catch (Exception $e) {
    $soundResponse = null;
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sound Level Ruangan A</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .status { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .data-display { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .json-display { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 5px; font-family: monospace; white-space: pre-wrap; margin: 15px 0; }
        .refresh-info { font-size: 0.9em; color: #666; margin: 10px 0; }
        .btn { background: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔊 Sound Level Ruangan A</h1>
        
        <div class="refresh-info">
            <span id="last-update">Loading...</span> | 
            <span id="refresh-counter">Updates: 0</span> |
            <button class="btn" onclick="manualRefresh()">Refresh Now</button>
        </div>
        
        <div id="status-display">
            <?php if ($error): ?>
                <div class="status error">
                    <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php elseif ($soundResponse): ?>
                <div class="status success" id="connection-status">
                    <strong>Status:</strong> <span id="status-text"><?= htmlspecialchars($soundResponse['status'] ?? 'Unknown') ?></span> | 
                    <strong>Message:</strong> <span id="message-text"><?= htmlspecialchars($soundResponse['message'] ?? 'No message') ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div id="data-display">
            <?php if ($soundResponse && isset($soundResponse['data'])): ?>
                <div class="data-display">
                    <h3>📊 Current Sound Data</h3>
                    <p><strong>Amplitude:</strong> <span id="amplitude-value"><?= htmlspecialchars($soundResponse['data']['amplitude'] ?? 'N/A') ?></span></p>
                    <p><strong>Decibels (dB):</strong> <span id="db-value"><?= htmlspecialchars($soundResponse['data']['db'] ?? 'N/A') ?></span></p>
                    <p><strong>Is Loud:</strong> <span id="loud-value"><?= $soundResponse['data']['isLoud'] ? 'Yes' : 'No' ?></span></p>
                </div>
                
                <h3>🔗 Firebase Response (JSON)</h3>
                <div class="json-display" id="json-response">
<?= htmlspecialchars(json_encode($soundResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?>
                </div>
            <?php else: ?>
                <div class="data-display">
                    <p>Data tidak tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="/dashboard" class="btn">🏠 Go to Dashboard</a>
            <a href="/logout.php" class="btn">🚪 Logout</a>
        </div>
    </div>

    <script>
        let refreshCounter = 0;
        let autoRefreshInterval;

        function updateDisplay(response) {
            refreshCounter++;
            document.getElementById('refresh-counter').textContent = `Updates: ${refreshCounter}`;
            document.getElementById('last-update').textContent = `Last update: ${new Date().toLocaleTimeString()}`;

            if (response.status) {
                document.getElementById('status-text').textContent = response.status;
                document.getElementById('message-text').textContent = response.message || 'No message';
                
                // Update connection status style
                const statusDiv = document.getElementById('connection-status');
                if (statusDiv) {
                    statusDiv.className = response.status === 'success' ? 'status success' : 'status error';
                }
            }

            if (response.data) {
                document.getElementById('amplitude-value').textContent = response.data.amplitude || 'N/A';
                document.getElementById('db-value').textContent = response.data.db || 'N/A';
                document.getElementById('loud-value').textContent = response.data.isLoud ? 'Yes' : 'No';
                
                // Update JSON display
                document.getElementById('json-response').textContent = JSON.stringify(response, null, 2);
            }
        }

        function fetchLatestData() {
            fetch('/api/sound-data')
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.response) {
                        updateDisplay(result.response);
                    } else {
                        console.warn('No valid response data received');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    document.getElementById('last-update').textContent = `Error: ${new Date().toLocaleTimeString()}`;
                });
        }

        function manualRefresh() {
            fetchLatestData();
        }

        function startAutoRefresh() {
            // Clear any existing interval
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
            
            // Fetch immediately
            fetchLatestData();
            
            // Set up automatic refresh every 1000ms (1 second)
            autoRefreshInterval = setInterval(fetchLatestData, 1000);
        }

        // Start auto-refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
        });

        // Keep refreshing when page becomes visible again
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                startAutoRefresh();
            }
        });

        // Stop refresh when leaving page
        window.addEventListener('beforeunload', function() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        });
    </script>
</body>
</html>
