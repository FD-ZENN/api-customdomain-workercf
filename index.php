<?php
// Cloudflare API Configuration
$account_id = 'isikan account id';
$auth_email = 'isikan email';
$auth_key = 'api token';
$zone_id = 'isikan zone id';
$service = 'isikan nama service worker';
$domain = 'isikan nama domain';

// Telegram Bot Configuration
$bot_token = 'isikan bot token';
$chat_id = 'isikan chat id';

// Function to send Telegram notification
function sendTelegramNotification($bot_token, $chat_id, $message) {
    $url = "https://api.telegram.org/bot$bot_token/sendMessage";
    
    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

# Handle messages from URL parameters (after redirect)
if (isset($_GET['success'])) {
    $message = $_GET['success'];
    $message_type = 'success';
} elseif (isset($_GET['error'])) {
    $message = $_GET['error'];
    $message_type = 'error';
}

// Handle form submission
$message = '';
$message_type = '';

if (isset($_POST['action']) && $_POST['action'] === 'add_domain') {
    $wildcard = trim($_POST['wildcard']);
    $subdomain = trim($_POST['subdomain']);
    
    // Build hostname: wildcard.subdomain.domain
    $hostname = $wildcard . '.' . $subdomain . '.' . $domain;
    
    // Prepare cURL for adding domain
    $url = "https://api.cloudflare.com/client/v4/accounts/$account_id/workers/domains";
    
    $data = json_encode([
        'environment' => 'production',
        'hostname' => $hostname,
        'service' => $service,
        'zone_id' => $zone_id
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "X-Auth-Email: $auth_email",
        "X-Auth-Key: $auth_key"
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($http_code === 200 && $result['success']) {
        // Send Telegram notification for success
        $telegram_message = "🎉 <b>Domain Berhasil Ditambahkan</b>\n\n";
        $telegram_message .= "📍 <b>Hostname:</b> <code>$hostname</code>\n";
        $telegram_message .= "⚙️ <b>Service:</b> $service\n";
        $telegram_message .= "🌐 <b>Zone:</b> $domain\n";
        $telegram_message .= "🔧 <b>Environment:</b> production\n";
        $telegram_message .= "⏰ <b>Waktu:</b> " . date('Y-m-d H:i:s') . "\n\n";
        $telegram_message .= "✅ Domain siap digunakan!";
        
        sendTelegramNotification($bot_token, $chat_id, $telegram_message);
        
        // Redirect to prevent duplicate submissions
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=" . urlencode("Domain berhasil ditambahkan: $hostname"));
        exit();
    } else {
        $error_msg = $result['errors'][0]['message'] ?? 'Unknown error';
        
        // Send Telegram notification for error
        $telegram_message = "❌ <b>Error Menambahkan Domain</b>\n\n";
        $telegram_message .= "📍 <b>Hostname:</b> <code>$hostname</code>\n";
        $telegram_message .= "⚠️ <b>Error:</b> $error_msg\n";
        $telegram_message .= "📊 <b>HTTP Code:</b> $http_code\n";
        $telegram_message .= "⏰ <b>Waktu:</b> " . date('Y-m-d H:i:s') . "\n\n";
        $telegram_message .= "🔧 Silakan periksa konfigurasi dan coba lagi.";
        
        sendTelegramNotification($bot_token, $chat_id, $telegram_message);
        
        // Redirect to prevent duplicate submissions
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . urlencode("Error menambahkan domain: $error_msg"));
        exit();
    }
}

// Function to get domains
function getDomains($account_id, $auth_email, $auth_key) {
    $url = "https://api.cloudflare.com/client/v4/accounts/$account_id/workers/domains";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-Auth-Email: $auth_email",
        "X-Auth-Key: $auth_key"
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Get current domains
$domains_response = getDomains($account_id, $auth_email, $auth_key);
$domains = $domains_response['result'] ?? [];

// Sort domains by subdomain (second part of hostname)
usort($domains, function($a, $b) {
    $hostname_a = $a['hostname'];
    $hostname_b = $b['hostname'];
    
    // Split hostname by dots and get the subdomain part (index 1)
    $parts_a = explode('.', $hostname_a);
    $parts_b = explode('.', $hostname_b);
    
    // Get subdomain (second part) for comparison
    $subdomain_a = isset($parts_a[1]) ? $parts_a[1] : '';
    $subdomain_b = isset($parts_b[1]) ? $parts_b[1] : '';
    
    return strcmp($subdomain_a, $subdomain_b);
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloudflare Worker Domains</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            background: #3498db;
            color: white;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
            font-size: 0.9rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
            background: white;
            cursor: pointer;
        }
        
        .form-group input:disabled {
            background: #f8f9fa;
            color: #6c757d;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .separator {
            color: #7f8c8d;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        
        .preview {
            background: #ecf0f1;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .preview strong {
            color: #2c3e50;
        }
        
        .btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .btn:active {
            transform: translateY(1px);
        }
        
        .message {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .domain-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .domain-item:last-child {
            border-bottom: none;
        }
        
        .domain-hostname {
            font-weight: 600;
            color: #2c3e50;
            word-break: break-all;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }
        
        .count {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .separator {
                margin: 5px 0;
                text-align: center;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            
            .card-body {
                padding: 12px;
            }
            
            .form-group input {
                padding: 10px;
            }
            
            .btn {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌐 Cloudflare Worker Domains</h1>
            <p>Kelola domain untuk Cloudflare Workers</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                ➕ Tambah Domain Baru
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_domain">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="wildcard">Wildcard</label>
                            <input type="text" id="wildcard" name="wildcard" placeholder="zoom.us" required>
                        </div>
                        
                        <div class="separator">+</div>
                        
                        <div class="form-group">
                            <label for="subdomain">Subdomain</label>
                            <select id="subdomain" name="subdomain" required>
                                <option value="">Pilih Subdomain</option>
                                <option value="vip-id1">vip-id1</option>
                                <option value="vip-id2">vip-id2</option>
                                <option value="vip-id3">vip-id3</option>
                                <option value="vip-sg1">vip-sg1</option>
                            </select>
                        </div>
                        
                        <div class="separator">+</div>
                        
                        <div class="form-group">
                            <label>Domain</label>
                            <input type="text" value="<?php echo $domain; ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="preview">
                        <strong>Preview:</strong> 
                        <span id="hostname-preview">wildcard.subdomain.<?php echo $domain; ?></span>
                    </div>
                    
                    <button type="submit" class="btn">🚀 Tambah Domain</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                📋 Daftar Domain Aktif <span class="count">(<?php echo count($domains); ?>)</span>
            </div>
            <div class="card-body">
                <?php if (empty($domains)): ?>
                    <div class="empty-state">
                        <p>Belum ada domain</p>
                        <small>Tambahkan domain pertama Anda menggunakan form di atas</small>
                    </div>
                <?php else: ?>
                    <?php foreach ($domains as $domain_item): ?>
                        <div class="domain-item">
                            <div class="domain-hostname"><?php echo htmlspecialchars($domain_item['hostname']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Real-time hostname preview
        const wildcardInput = document.getElementById('wildcard');
        const subdomainInput = document.getElementById('subdomain');
        const hostnamePreview = document.getElementById('hostname-preview');
        const baseDomain = '<?php echo $domain; ?>';
        
        function updatePreview() {
            const wildcard = wildcardInput.value || 'wildcard';
            const subdomain = subdomainInput.value || 'subdomain';
            hostnamePreview.textContent = `${wildcard}.${subdomain}.${baseDomain}`;
        }
        
        wildcardInput.addEventListener('input', updatePreview);
        subdomainInput.addEventListener('change', updatePreview);
        
        ;
    </script>
</body>
</html>
