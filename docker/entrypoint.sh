#!/bin/sh
set -e

echo "=== Lacera Entrypoint ==="

# Ensure PORT is set (Render provides this, default 10000)
export PORT="${PORT:-10000}"
echo "PORT is: ${PORT}"

# Ensure APP_URL is set and valid (catch empty, bare scheme, or placeholder values with < >)
APP_URL_INVALID=false
if [ -z "${APP_URL}" ] || [ "${APP_URL}" = "http://" ] || [ "${APP_URL}" = "https://" ]; then
    APP_URL_INVALID=true
elif echo "${APP_URL}" | grep -q '[<>]'; then
    echo "WARNING: APP_URL contains placeholder characters: '${APP_URL}'"
    APP_URL_INVALID=true
fi

if [ "${APP_URL_INVALID}" = "true" ]; then
    if [ -n "${RENDER_EXTERNAL_URL}" ]; then
        export APP_URL="${RENDER_EXTERNAL_URL}"
        echo "APP_URL auto-set from RENDER_EXTERNAL_URL: ${APP_URL}"
    else
        export APP_URL="http://localhost:${PORT}"
        echo "APP_URL defaulted to: ${APP_URL}"
    fi
else
    echo "APP_URL is: '${APP_URL}'"
fi

# Force APP_URL to use https:// when on Render (not localhost)
if echo "${APP_URL}" | grep -qv 'localhost'; then
    APP_URL=$(echo "${APP_URL}" | sed 's|^http://|https://|')
    export APP_URL
    echo "APP_URL forced to HTTPS: ${APP_URL}"
fi

# Create .env file if it doesn't exist (required by Laravel)
if [ ! -f .env ]; then
    echo "No .env file found, creating from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        touch .env
    fi
fi

# Substitute PORT in nginx config template
echo "Configuring Nginx to listen on port ${PORT}..."
envsubst '${PORT}' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# Create storage link if not exists
php artisan storage:link --force 2>/dev/null || true

# Inject runtime settings into .env for production on Render
sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|^SESSION_DRIVER=.*|SESSION_DRIVER=database|" .env

# Ensure HTTPS session settings exist in .env
if ! grep -q '^SESSION_SECURE_COOKIE=' .env; then
    echo "SESSION_SECURE_COOKIE=true" >> .env
else
    sed -i "s|^SESSION_SECURE_COOKIE=.*|SESSION_SECURE_COOKIE=true|" .env
fi

# Generate application key if not set
php artisan key:generate --force --no-interaction 2>/dev/null || true

# Cache configuration
echo "Caching Laravel configuration..."
php artisan config:cache || echo "WARNING: config:cache failed, continuing without cache..."
php artisan route:cache || echo "WARNING: route:cache failed, continuing without cache..."
php artisan view:cache || echo "WARNING: view:cache failed, continuing without cache..."

# Run database migrations (optional)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "WARNING: migrations failed"
fi

# Run custom commands if passed, else start supervisord
if [ $# -gt 0 ]; then
    exec "$@"
else
    echo "Starting Supervisor..."
    mkdir -p /var/log/supervisor
    exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
