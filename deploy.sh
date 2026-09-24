#!/usr/bin/env bash
# ==============================================================================
# Absolute Store - Zero-Downtime Production Deployment Script
# ==============================================================================
set -e

echo "🚀 Memulai proses deployment Absolute Store Production..."

# 1. Masuk ke maintenance mode sementara (jika diperlukan)
php artisan down --render="errors::503" --secret="absolute-store-deploy-bypass" || true

# 2. Tarik kode repositori terbaru
echo "📦 Mengambil perubahan kode terbaru dari Git..."
git pull origin main

# 3. Instal dependensi Composer (Production Optimized)
echo "🐘 Mengoptimasi PHP Composer dependencies..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 4. Instal dependensi NPM & Bangun Aset Frontend (Vite)
echo "🎨 Membangun bundel aset frontend Vite & Tailwind CSS..."
npm ci --silent
npm run build

# 5. Jalankan migrasi basis data
echo "🗄️ Menjalankan migrasi database..."
php artisan migrate --force

# 6. Bersihkan & bangun ulang cache konfigurasi Laravel
echo "⚡ Mengoptimasi cache route, view, dan konfigurasi aplikasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Pastikan symbolic link storage terhubung
php artisan storage:link || true

# 8. Restart queue workers (Supervisor) untuk memuat kode baru
echo "🔄 Merestart background queue workers..."
php artisan queue:restart

# 9. Nonaktifkan maintenance mode
php artisan up

echo "✅ Deployment Absolute Store berhasil diselesaikan tanpa hambatan!"
