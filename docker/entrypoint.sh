#!/bin/sh
set -e

echo "=== Lacera Entrypoint (Railway) ==="

# PORT — Railway inject ini otomatis
export PORT="${PORT:-8080}"
echo "PORT is: ${PORT}"

# APP_URL — validasi dan force HTTPS
if [ -z "${APP_URL}" ] || echo "${APP_URL}" | grep -q '[<>]'; then
    export APP_URL="http://localhost:${PORT}"
    echo "APP_URL defaulted to: ${APP_URL}"
else
    echo "APP_URL is: '${APP_URL}'"
fi

# Force HTTPS kalau bukan localhost
if echo "${APP_URL}" | grep -qv 'localhost'; then
    APP_URL=$(echo "${APP_URL}" | sed 's|^http://|https://|')
    export APP_URL
    echo "APP_URL forced to HTTPS: ${APP_URL}"
fi

# Buat .env dari environment variables Railway (bukan dari .env.example)
echo "Creating .env from Railway environment variables..."
cat > .env << EOF
APP_NAME="${APP_NAME:-Lacera}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL}"

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER="${SESSION_DRIVER:-file}"
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true

CACHE_DRIVER="${CACHE_DRIVER:-file}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

PAYMENKU_API_KEY="${PAYMENKU_API_KEY}"
PAYMENKU_BASE_URL="${PAYMENKU_BASE_URL}"
RAJAONGKIR_API_KEY="${RAJAONGKIR_API_KEY}"
RAJAONGKIR_ORIGIN_CITY_ID="${RAJAONGKIR_ORIGIN_CITY_ID:-23}"
EOF

echo ".env created successfully"

# Konfigurasi Nginx dengan PORT yang benar
echo "Configuring Nginx on port ${PORT}..."
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# Storage link
php artisan storage:link --force 2>/dev/null || true

# Clear semua cache lama supaya .env baru terbaca
php artisan config:clear 2>/dev/null || true
php artisan cache:clear  2>/dev/null || true

# Cache ulang dengan config baru
echo "Caching configuration..."
php artisan config:cache || echo "WARNING: config:cache failed"
php artisan route:cache  || echo "WARNING: route:cache failed"
php artisan view:cache   || echo "WARNING: view:cache failed"

# Jalankan migration
echo "Running database migrations..."
php artisan migrate --force || echo "WARNING: migrations failed, check DB connection"

# Start supervisor (nginx + php-fpm)
echo "Starting Supervisor..."
mkdir -p /var/log/supervisor
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf