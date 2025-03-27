const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const sourceDir = path.join(__dirname, 'node_modules', 'leaflet', 'dist', 'images');
const targetDir = path.join(__dirname, 'public', 'vendor', 'leaflet', 'images');

if (!fs.existsSync(targetDir)) {
  fs.mkdirSync(targetDir, { recursive: true });
}

const files = fs.readdirSync(sourceDir);

files.forEach(file => {
  const sourceFile = path.join(sourceDir, file);
  const targetFile = path.join(targetDir, file);
  fs.copyFileSync(sourceFile, targetFile);
});

console.log('Leaflet images copied successfully.');
