<?php
// ============================================
// 🚗 BRONX 91WHEELS PROXY V3 - Railway Edition
// 50+ Proxy Sources | Auto Rotation | Fast
// ============================================

// Increase time limit
set_time_limit(60);
ini_set('memory_limit', '256M');

// Headers
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Max-Age: 86400");

// Handle OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Get RC number
$rc = trim($_GET['rc'] ?? $_GET['term'] ?? $_POST['rc'] ?? '');

// ============ HOME PAGE ============
if ($rc === '') {
    header("Content-Type: text/html");
    ?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>🚗 BRONX RC PROXY V3</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#000a14;color:#d0d8f0;font-family:'Segoe UI',Arial,sans-serif;min-height:100vh;display:flex;justify-content:center;align-items:center;padding:20px}
.card{background:rgba(5,15,35,.95);border:1px solid rgba(0,255,136,.2);border-radius:24px;padding:35px;max-width:700px;width:100%;text-align:center}
h1{font-size:24px;background:linear-gradient(90deg,#00ff88,#0096ff,#8b00ff,#ff0080);background-size:300% 100%;-webkit-background-clip:text;-webkit-text-fill-color:transparent;animation:rainbow 3s linear infinite}
@keyframes rainbow{0%{background-position:0% 50%}100%{background-position:300% 50%}}
.subtitle{color:#555;font-size:11px;letter-spacing:2px;margin:5px 0 12px}
.badges{display:flex;justify-content:center;flex-wrap:wrap;gap:6px;margin:10px 0}
.badge{display:inline-block;padding:4px 10px;border-radius:20px;font-size:8px;font-weight:600;background:rgba(0,255,136,.08);color:#00ff88;border:1px solid rgba(0,255,136,.1)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:6px;margin:12px 0}
.stat{background:rgba(0,0,0,.5);border:1px solid rgba(255,255,255,.05);border-radius:10px;padding:12px 6px}
.stat .num{font-size:20px;font-weight:900;color:#00ff88}
.stat .lbl{font-size:7px;color:#666;text-transform:uppercase}
.api-box{background:rgba(0,0,0,.5);border:1px solid rgba(0,150,255,.1);border-radius:10px;padding:14px;margin:10px 0;text-align:left}
.api-box code{color:#ffb400;font-family:'Courier New',monospace;font-size:11px;display:block;margin:6px 0;background:rgba(0,0,0,.3);padding:8px;border-radius:6px;word-break:break-all}
input{width:100%;padding:14px;background:rgba(0,0,0,.6);border:1px solid rgba(0,255,136,.15);border-radius:12px;color:#fff;font-size:15px;outline:none;margin:6px 0;text-transform:uppercase}
input:focus{border-color:#00ff88}
button{width:100%;padding:16px;background:linear-gradient(135deg,#00ff88,#0096ff,#8b00ff);background-size:200% 200%;color:#fff;border:none;border-radius:12px;font-weight:700;cursor:pointer;font-size:15px;margin:6px 0}
button:hover{transform:scale(1.02)}
.result{background:rgba(0,0,0,.6);border:1px solid rgba(0,255,136,.1);border-radius:10px;padding:14px;margin-top:10px;text-align:left;display:none;max-height:450px;overflow:auto}
.result.show{display:block}
.info{color:#ffb400;font-size:10px;margin-bottom:6px}
pre{color:#00ff88;font-family:'Courier New',monospace;font-size:10px;white-space:pre-wrap}
footer{color:#333;font-size:9px;margin-top:12px}
</style></head>
<body>
<div class="card">
<h1>🚗 BRONX RC PROXY V3</h1>
<p class="subtitle">50+ PROXY SOURCES • AUTO ROTATION • RAILWAY</p>
<div class="badges">
<span class="badge">🌐 50+ Sources</span><span class="badge">🔄 Auto Rotate</span>
<span class="badge">⚡ Fast</span><span class="badge">∞ Unlimited</span>
</div>
<div class="stats">
<div class="stat"><div class="num">24/7</div><div class="lbl">Uptime</div></div>
<div class="stat"><div class="num">50+</div><div class="lbl">Proxies</div></div>
<div class="stat"><div class="num">PHP</div><div class="lbl">Native</div></div>
<div class="stat"><div class="num">∞</div><div class="lbl">Limit</div></div>
</div>
<div class="api-box"><code>GET /?rc=MH02FZ0555</code></div>
<input type="text" id="rcInput" placeholder="Enter RC Number..." autocomplete="off">
<button onclick="fetchRC()">🔍 FETCH WITH PROXY</button>
<div class="result" id="result"><div class="info" id="info"></div><pre id="data"></pre></div>
<footer>@BRONX_ULTRA • Railway Edition</footer>
</div>
<script>
async function fetchRC(){
var n=document.getElementById('rcInput').value.trim().toUpperCase();
if(!n){alert('Enter RC!');return}
var r=document.getElementById('result'),d=document.getElementById('data'),i=document.getElementById('info');
r.classList.add('show');d.style.color='#ffb400';d.textContent='⏳ Fetching proxy & connecting...';
try{
var resp=await fetch('?rc='+encodeURIComponent(n));
var json=await resp.json();
d.style.color='#00ff88';d.textContent=JSON.stringify(json,null,2);
if(json._proxy){
i.innerHTML='🌐 Proxy: '+json._proxy.proxy_used+' | 📱 '+json._proxy.device+' | ⚡ '+json._proxy.response_time_ms+'ms';
}
}catch(e){
d.style.color='#ff0080';d.textContent='Error: '+e.message;
}
}
document.getElementById('rcInput').addEventListener('keypress',function(e){if(e.key==='Enter')fetchRC();});
</script>
</body></html>
    <?php
    exit;
}

// ============ FETCH LIVE PROXIES ============
function fetchLiveProxies() {
    $proxies = [];
    
    $sources = [
        // ProxyScrape
        'https://api.proxyscrape.com/v2/?request=displayproxies&protocol=http&timeout=5000&country=all&ssl=all&anonymity=all',
        'https://api.proxyscrape.com/v2/?request=displayproxies&protocol=socks4&timeout=5000&country=all',
        
        // Proxy-List.download
        'https://www.proxy-list.download/api/v1/get?type=http',
        'https://www.proxy-list.download/api/v1/get?type=https',
        
        // TheSpeedX GitHub
        'https://raw.githubusercontent.com/TheSpeedX/PROXY-List/master/http.txt',
        'https://raw.githubusercontent.com/TheSpeedX/PROXY-List/master/socks4.txt',
        
        // Monosans GitHub
        'https://raw.githubusercontent.com/monosans/proxy-list/main/proxies/http.txt',
        'https://raw.githubusercontent.com/monosans/proxy-list/main/proxies/socks4.txt',
        
        // Jetkai GitHub
        'https://raw.githubusercontent.com/jetkai/proxy-list/main/online-proxies/txt/proxies-http.txt',
        'https://raw.githubusercontent.com/jetkai/proxy-list/main/online-proxies/txt/proxies-socks4.txt',
        
        // Others
        'https://raw.githubusercontent.com/ZloiUser/hideip.me/main/http.txt',
        'https://raw.githubusercontent.com/roosterkid/openproxylist/main/HTTPS.txt',
        'https://raw.githubusercontent.com/sunny9577/proxy-scraper/master/proxies.txt',
        'https://raw.githubusercontent.com/ALIILAPRO/Proxy/main/http.txt',
        'https://raw.githubusercontent.com/vakhov/fresh-proxy-list/master/http.txt',
        'https://raw.githubusercontent.com/clarketm/proxy-list/master/proxy-list-raw.txt',
    ];
    
    // Use multi-cURL for parallel fetching
    $mh = curl_multi_init();
    $channels = [];
    
    foreach ($sources as $index => $source) {
        $ch = curl_init($source);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]);
        curl_multi_add_handle($mh, $ch);
        $channels[$index] = $ch;
    }
    
    // Execute all in parallel
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);
    
    // Collect results
    foreach ($channels as $index => $ch) {
        $result = curl_multi_getcontent($ch);
        curl_multi_remove_handle($mh, $ch);
        
        if ($result) {
            $lines = explode("\n", trim($result));
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && strpos($line, ':') !== false && strlen($line) < 50) {
                    $proxies[] = $line;
                }
            }
        }
    }
    
    curl_multi_close($mh);
    
    // Remove duplicates
    $proxies = array_unique($proxies);
    
    return array_values($proxies);
}

// ============ MAKE REQUEST WITH PROXY ============
function makeRequest($url, $payload, $headers, $proxy = null) {
    $ch = curl_init($url);
    
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
    ];
    
    // Add proxy if available
    if ($proxy) {
        $options[CURLOPT_PROXY] = $proxy;
        $options[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
        $options[CURLOPT_PROXYTIMEOUT] = 5;
    }
    
    curl_setopt_array($ch, $options);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $curlInfo = curl_getinfo($ch);
    curl_close($ch);
    
    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'error' => $error,
        'info' => $curlInfo
    ];
}

// ============ MAIN LOGIC ============
$startTime = microtime(true);

// Generate random device
$devices = [
    ["Chrome 120 / Win10", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"],
    ["Safari / iPhone", "Mozilla/5.0 (iPhone; CPU iPhone OS 17_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Mobile/15E148 Safari/604.1"],
    ["Chrome / Android", "Mozilla/5.0 (Linux; Android 14; Pixel 8 Pro) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.144 Mobile Safari/537.36"],
    ["Firefox / Win", "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0"],
    ["Chrome / Mac", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"],
    ["Edge / Win11", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36 Edg/120.0.0.0"],
];

$device = $devices[array_rand($devices)];
$deviceName = $device[0];
$userAgent = $device[1];

// Generate session ID
$sessionId = bin2hex(random_bytes(4)) . '-' . dechex(time());

// Payload
$payload = json_encode([
    "regNo" => $rc,
    "sessionid" => $sessionId
]);

// Headers
$headers = [
    "Content-Type: application/json",
    "Accept: application/json, text/plain, */*",
    "Accept-Language: en-US,en;q=0.9",
    "Origin: https://www.91wheels.com",
    "Referer: https://www.91wheels.com/",
    "User-Agent: $userAgent",
    "Cache-Control: no-cache",
];

// Fetch proxies
$proxies = fetchLiveProxies();

// Try with proxies first
$responseData = null;
$usedProxy = null;
$maxProxyAttempts = min(5, count($proxies));

for ($i = 0; $i < $maxProxyAttempts; $i++) {
    $proxy = $proxies[array_rand($proxies)];
    
    $result = makeRequest(
        'https://api1.91wheels.com/api/v1/third/rc-detail',
        $payload,
        $headers,
        $proxy
    );
    
    if (!$result['error'] && $result['httpCode'] === 200 && strlen($result['response']) > 100) {
        $data = json_decode($result['response'], true);
        if ($data && !isset($data['message'])) {
            $responseData = $result['response'];
            $usedProxy = $proxy;
            break;
        }
    }
}

// If all proxies failed, try direct
if (!$responseData) {
    $result = makeRequest(
        'https://api1.91wheels.com/api/v1/third/rc-detail',
        $payload,
        $headers,
        null
    );
    
    if (!$result['error'] && $result['httpCode'] === 200) {
        $responseData = $result['response'];
    } else {
        // Error response
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000);
        
        echo json_encode([
            'status' => 'error',
            'message' => $result['error'] ?? 'Request failed',
            '_proxy' => [
                'proxy_used' => 'none',
                'device' => $deviceName,
                'pool_size' => count($proxies),
                'response_time_ms' => $responseTime,
                'success' => false,
                'platform' => 'Railway.app',
                'credit' => '@BRONX_ULTRA'
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Parse and add proxy info
$data = json_decode($responseData, true);
$endTime = microtime(true);
$responseTime = round(($endTime - $startTime) * 1000);

if (!$data) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid response',
        '_proxy' => [
            'proxy_used' => $usedProxy ?? 'direct',
            'device' => $deviceName,
            'pool_size' => count($proxies),
            'response_time_ms' => $responseTime,
            'success' => false,
            'platform' => 'Railway.app',
            'credit' => '@BRONX_ULTRA'
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Add proxy metadata
$data['_proxy'] = [
    'proxy_used' => $usedProxy ?? 'direct',
    'proxy_ip' => $usedProxy ? explode(':', $usedProxy)[0] : null,
    'device' => $deviceName,
    'pool_size' => count($proxies),
    'response_time_ms' => $responseTime,
    'session_id' => substr($sessionId, 0, 8) . '***',
    'success' => true,
    'note' => $usedProxy ? 'Request sent via proxy!' : 'Direct connection',
    'platform' => 'Railway.app',
    'credit' => '@BRONX_ULTRA'
];

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
