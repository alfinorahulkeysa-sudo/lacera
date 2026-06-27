#!/bin/sh
set -e

echo "=== Lacera Entrypoint ==="

# Ensure PORT is set (Render provides this, default 10000)
export PORT="${PORT:-10000}"
echo "PORT is: ${PORT}"

# Ensure APP_URL is set and valid
if [ -z "${APP_URL}" ] || [ "${APP_URL}" = "http://" ] || [ "${APP_URL}" = "https://" ]; then
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
    exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
