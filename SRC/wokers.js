// Vehicle Info API Worker
addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request))
})

async function handleRequest(request) {
  const url = new URL(request.url);
  const path = url.pathname;
  const method = request.method;
  
  const corsHeaders = {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type, Authorization',
    'Access-Control-Max-Age': '86400',
  };

  if (method === 'OPTIONS') {
    return new Response(null, { headers: corsHeaders });
  }

  try {
    if (path === '/' || path === '') {
      return handleHomePage(corsHeaders);
    } else if (path === '/health') {
      return handleHealth(corsHeaders);
    } else if (path.startsWith('/vehicle/')) {
      const vehicleNumber = path.split('/vehicle/')[1];
      return await handleVehicleInfo(vehicleNumber, corsHeaders);
    } else if (path.startsWith('/raw/')) {
      const vehicleNumber = path.split('/raw/')[1];
      return await handleRawVehicle(vehicleNumber, corsHeaders);
    } else if (path === '/profile') {
      return await handleUserProfile(corsHeaders);
    } else {
      return new Response(JSON.stringify({ 
        error: 'Not found',
        endpoints: ['/', '/health', '/vehicle/{number}', '/raw/{number}', '/profile']
      }), {
        status: 404,
        headers: { 'Content-Type': 'application/json', ...corsHeaders }
      });
    }
  } catch (error) {
    return new Response(JSON.stringify({ error: error.message }), {
      status: 500,
      headers: { 'Content-Type': 'application/json', ...corsHeaders }
    });
  }
}

function getCommonHeaders() {
  return {
    'User-Agent': 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36',
    'Accept-Encoding': 'gzip, deflate, br',
    'pragma': 'no-cache',
    'cache-control': 'no-cache',
    'sec-ch-ua-platform': '"Android"',
    'authorization': 'Bearer eyJhbGciOiJSUzI1NiIsImtpZCI6IjI3YzQ1NTQ4NTU1NTYxOTYwZjQ5MWQ1MDYzOWU1NTY1N2IyMTJhYmMiLCJ0eXAiOiJKV1QifQ.eyJuYW1lIjoiSlVUSElLQSBNT05EQUwiLCJwaWN0dXJlIjoiaHR0cHM6Ly9saDMuZ29vZ2xldXNlcmNvbnRlbnQuY29tL2EvQUNnOG9jS3RvWkxXN0NtR2xKYUVYNi1rTm9nMDlaWUxtVXhMZUJiTmI2UE5nUlpUZHE0UGh4Zz1zOTYtYyIsImlzcyI6Imh0dHBzOi8vc2VjdXJldG9rZW4uZ29vZ2xlLmNvbS92ZWhpY2xlLWluZm8tYXBwIiwiYXVkIjoidmVoaWNsZS1pbmZvLWFwcCIsImF1dGhfdGltZSI6MTc4Njk0MjgzNiwidXNlcl9pZCI6Ilh1Z2FzOXFNT2VhazQwWFc1WnRSbmNoZTlCUzIiLCJzdWIiOiJYdWdhczlxTU9lYWs0MFhXNVp0Um5jaGU5QlMyIiwiaWF0IjoxNzg2OTQyODM2LCJleHAiOjE3ODY5NDY0MzYsImVtYWlsIjoicmlqdW1hbmRhbG9wb3BAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImZpcmViYXNlIjp7ImlkZW50aXRpZXMiOnsiZ29vZ2xlLmNvbSI6WyIxMTc4MDc5ODczNzc0Njg3MzE4OTAiXSwiZW1haWwiOlsicmlqdW1hbmRhbG9wb3BAZ21haWwuY29tIl19LCJzaWduX2luX3Byb3ZpZGVyIjoiZ29vZ2xlLmNvbSJ9fQ.QgDolqmnPUjHoIKI5oogu6sn0BvdtRLcipGOYQZg9oMacKAZEN9_d6DEuK-ymtn9DHVQO64qlojhJixP1eKmSER8bbrh9am4cjyEeOvZAIa5JN8_azaDordUiS2Roxt2rqA0ykskHH3c7RpHvYWg6EuHvLIl7iodb9jJzGf8ziD-TY9GYbzOVm9QMdopVtGj4szULUWt__EWYLFEqEXrpGTO2Hyt8I3-5Bmhhm-seh2UzVEF1nGO6ChHbNkRgF2HJTE4jgAGK9LLXmjKZwOqgLAHZKDuAxvkQhjngHlPp0SxIDnQpSLAmynJu-5D_r97Ff_E3K6X1nwwTvafhLrwng',
    'device_os': 'web',
    'device_type': 'web',
    'sec-ch-ua': '"Not=A?Brand";v="99", "Brave";v="151", "Chromium";v="151"',
    'sec-ch-ua-mobile': '?1',
    'sec-gpc': '1',
    'sec-fetch-site': 'same-origin',
    'sec-fetch-mode': 'cors',
    'sec-fetch-dest': 'empty',
    'referer': 'https://www.mymotor.in/',
    'accept-language': 'en-US,en;q=0.8',
    'priority': 'u=1, i',
    'Cookie': 'cf_clearance=OtxxHG7QMU2x2YlFjuHv1Ijyw0SdI8iKzt_bbDt_jLc-1786942816-1.2.1.1-N4zwgwx4as8LaGKQz8RxwcLMQvuelazpojN2GfeUTEjm7IxAKe0oZDtlWupKwc5CPXAaRI2XO4khUjZPR2sOy3KN014.6czYCrTxT6jmwDcMsSfYTvmN35s4xrPjdXxWBNEhDtZ79iPpwDI_RRtcwLh14vu4OIZ7Rr.SBQRUPiBoPx8z_VNzJgTXjT_ZSHs3OQ4haaxLe1zu0JjgsQ_frIsVe1YqR2P_mFSx3qge5X_NIwLce7S9k7HwF8wmljd7G.wOZ_F0UzTUpHA3zqi49QGgvHpi4yfQDt2kbI4_i07jw_zZRGHUPhzxzLM8Pv7Wgq.NgsiTLES_kH5aHWBSWnj6wzjoeSpHyYpWcs8EcfA; builderSessionId=9b07cd9dd5324ca594375cd7a60dd45a'
  };
}

async function handleVehicleInfo(vehicleNumber, corsHeaders) {
  const cleanNumber = vehicleNumber.trim().toUpperCase().replace(/ /g, '');
  
  if (!cleanNumber) {
    return new Response(JSON.stringify({ error: 'Vehicle number required' }), {
      status: 400,
      headers: { 'Content-Type': 'application/json', ...corsHeaders }
    });
  }

  const headers = getCommonHeaders();
  headers['Content-Type'] = 'application/json';
  headers['origin'] = 'https://www.mymotor.in';
  headers['referer'] = `https://www.mymotor.in/rc-details/${cleanNumber}`;

  const payload = {
    vehicle_number: cleanNumber,
    device_type: 'web',
    device_os: 'web',
    internal_search: false
  };

  const response = await fetch('https://www.mymotor.in/api/rc-search', {
    method: 'POST',
    headers: headers,
    body: JSON.stringify(payload)
  });

  const data = await response.json();

  return new Response(JSON.stringify(data, null, 2), {
    status: 200,
    headers: { 'Content-Type': 'application/json', ...corsHeaders }
  });
}

async function handleRawVehicle(vehicleNumber, corsHeaders) {
  const cleanNumber = vehicleNumber.trim().toUpperCase().replace(/ /g, '');
  
  const headers = getCommonHeaders();
  headers['Content-Type'] = 'application/json';
  headers['origin'] = 'https://www.mymotor.in';
  headers['referer'] = `https://www.mymotor.in/rc-details/${cleanNumber}`;

  const payload = {
    vehicle_number: cleanNumber,
    device_type: 'web',
    device_os: 'web',
    internal_search: false
  };

  const response = await fetch('https://www.mymotor.in/api/rc-search', {
    method: 'POST',
    headers: headers,
    body: JSON.stringify(payload)
  });

  const text = await response.text();

  return new Response(text, {
    status: response.status,
    headers: { 'Content-Type': 'application/json', ...corsHeaders }
  });
}

async function handleUserProfile(corsHeaders) {
  const headers = getCommonHeaders();
  headers['device_id'] = 'web_unknown';

  const response = await fetch('https://www.mymotor.in/api/user-profile', {
    method: 'GET',
    headers: headers
  });

  const data = await response.json();

  return new Response(JSON.stringify(data, null, 2), {
    status: 200,
    headers: { 'Content-Type': 'application/json', ...corsHeaders }
  });
}

function handleHealth(corsHeaders) {
  return new Response(JSON.stringify({
    status: 'healthy',
    platform: 'Cloudflare Workers',
    deployment: 'GitHub Connected',
    time: new Date().toISOString(),
    timestamp: Date.now()
  }, null, 2), {
    status: 200,
    headers: { 'Content-Type': 'application/json', ...corsHeaders }
  });
}

function handleHomePage(corsHeaders) {
  const html = `<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Info API - GitHub + Cloudflare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { 
            max-width: 900px; 
            width: 100%;
            background: white; 
            padding: 30px; 
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h2 { 
            color: #333; 
            text-align: center; 
            margin-bottom: 10px;
            font-size: 28px;
        }
        .status-badge {
            text-align: center;
            margin-bottom: 20px;
        }
        .badge {
            background: #4CAF50;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }
        .search-box { 
            display: flex; 
            gap: 10px; 
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        input { 
            flex: 1;
            min-width: 200px;
            padding: 15px; 
            font-size: 16px; 
            border: 2px solid #667eea; 
            border-radius: 10px;
            text-transform: uppercase;
            outline: none;
        }
        input:focus {
            border-color: #764ba2;
            box-shadow: 0 0 10px rgba(118, 75, 162, 0.2);
        }
        button { 
            padding: 15px 30px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 10px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        button:hover { 
            background: #5a67d8; 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        pre { 
            background: #1e1e1e; 
            color: #d4d4d4; 
            padding: 20px; 
            border-radius: 10px; 
            overflow-x: auto; 
            font-size: 13px;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 400px;
            overflow-y: auto;
        }
        .endpoints {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 13px;
        }
        .endpoints h3 {
            margin-bottom: 10px;
            color: #333;
        }
        .endpoint-item {
            padding: 5px 0;
            color: #666;
            font-family: monospace;
        }
        .loading {
            text-align: center;
            color: #667eea;
            padding: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🚗 Vehicle Info API</h2>
        <div class="status-badge">
            <span class="badge">🟢 24/7 Online - GitHub + Cloudflare Workers</span>
        </div>
        <div class="search-box">
            <input type="text" id="vehicle" placeholder="Enter Vehicle Number (MH02FZ0555)" />
            <button onclick="search()">🔍 Search</button>
        </div>
        <pre id="result" style="display:none;"></pre>
        <div class="endpoints">
            <h3>📡 Available Endpoints:</h3>
            <div class="endpoint-item">GET /health - Health Check</div>
            <div class="endpoint-item">GET /vehicle/{number} - Vehicle Info</div>
            <div class="endpoint-item">GET /raw/{number} - Raw Response</div>
            <div class="endpoint-item">GET /profile - User Profile</div>
        </div>
    </div>
    <script>
        async function search() {
            const vehicle = document.getElementById('vehicle').value.trim().toUpperCase();
            const result = document.getElementById('result');
            
            if (!vehicle) { 
                alert('Vehicle number enter karo!'); 
                return; 
            }
            
            result.style.display = 'block';
            result.innerHTML = '<div class="loading">⏳ Loading...</div>';
            
            try {
                const response = await fetch('/vehicle/' + vehicle);
                const data = await response.json();
                result.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                result.textContent = 'Error: ' + error.message;
            }
        }
        
        document.getElementById('vehicle').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') search();
        });
        
        // Auto-focus
        window.onload = function() {
            document.getElementById('vehicle').focus();
        };
    </script>
</body>
</html>`;

  return new Response(html, {
    status: 200,
    headers: { 'Content-Type': 'text/html', ...corsHeaders }
  });
}
