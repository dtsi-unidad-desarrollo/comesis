// Simple local agent that serves the machine MAC at http://localhost:3000/client-info
// Usage: node local-agent.js

const os = require('os');
const http = require('http');

function getFirstMac() {
  const nets = os.networkInterfaces();
  for (const name of Object.keys(nets)) {
    for (const net of nets[name]) {
      if (net.mac && net.mac !== '00:00:00:00:00:00' && !net.internal) {
        return net.mac;
      }
    }
  }
  return null;
}

const server = http.createServer((req, res) => {
  if (req.method === 'GET' && req.url === '/client-info') {
    const mac = getFirstMac();
    res.writeHead(200, {'Content-Type': 'application/json'});
    res.end(JSON.stringify({ mac }));
    return;
  }

  res.writeHead(404, {'Content-Type': 'application/json'});
  res.end(JSON.stringify({ message: 'Not found' }));
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => console.log(`Local agent running on http://localhost:${PORT}`));
