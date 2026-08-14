import { cpSync, existsSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const sourceDir = join(root, 'node_modules/gentelella/dist/images');
const targetDir = join(root, 'public/vendor/gentelella/images');

if (!existsSync(sourceDir)) {
    console.warn('Gentelella is not installed. Run npm install first.');
    process.exit(0);
}

mkdirSync(targetDir, { recursive: true });
cpSync(sourceDir, targetDir, { recursive: true });

console.log('Gentelella static assets copied to public/vendor/gentelella/images');
