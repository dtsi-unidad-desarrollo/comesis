// Simple Node script to register the machine MAC address with the server
// Usage: node register-client.js http://localhost:8000 register

const os = require('os');
const https = require('https');
const http = require('http');

function getFirstMac() {
  const nets = os.networkInterfaces();
  for (const name of Object.keys(nets)) {
    for (const net of nets[name]) {
      // skip internal (i.e. 127.0.0.1) and non-mac
      if (net.mac && net.mac !== '00:00:00:00:00:00' && !net.internal) {
        return net.mac;
      }
    }
  }
  return null;
}

async function register(serverUrl) {
  const mac = getFirstMac();
  if (!mac) {
    console.error('No MAC address found');
    process.exit(1);
  }

  const url = new URL('/register-client', serverUrl);
  const payload = JSON.stringify({ mac });

  const lib = url.protocol === 'https:' ? https : http;
  const options = {
    hostname: url.hostname,
    port: url.port || (url.protocol === 'https:' ? 443 : 80),
    path: url.pathname,
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Content-Length': Buffer.byteLength(payload),
    }
  };

  const req = lib.request(options, (res) => {
    let data = '';
    res.on('data', (chunk) => data += chunk);
    res.on('end', () => {
      console.log('Response:', res.statusCode, data);
    });
  });

  req.on('error', (e) => {
    console.error('Request error', e.message);
  });

  req.write(payload);
  req.end();
}

if (require.main === module) {
  const server = process.argv[2] || 'http://localhost:8000';
  register(server).catch(err => console.error(err));
}
