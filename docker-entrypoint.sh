#!/bin/sh
set -e

# 1. Pastikan file .env ada
if [ ! -f /app/.env ]; then
    echo "File .env tidak ditemukan, membuat dari .env.example..."
    cp /app/.env.example /app/.env
fi

# 2. Pastikan dependensi Composer terinstal
if [ ! -d /app/vendor ]; then
    echo "Folder vendor/ tidak ditemukan, menginstal dependensi composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 3. Pastikan APP_KEY terisi
if ! grep -q "APP_KEY=base64:" /app/.env; then
    echo "Generate APP_KEY..."
    php artisan key:generate --force
fi

# 4. Beri hak akses storage & cache
chmod -R 777 /app/storage /app/bootstrap/cache

# 5. Jalankan command utama (Octane)
echo "Menjalankan Laravel Octane dengan FrankenPHP..."
exec "$@"
