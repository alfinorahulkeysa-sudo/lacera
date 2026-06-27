#!/bin/sh
set -e

# Debug print of environment variables
echo "APP_URL is: '${APP_URL}'"
echo "DB_HOST is: '${DB_HOST}'"

# Cache configuration if variables are set
echo "Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations (optional, but highly recommended)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Run custom commands if passed, else start supervisord
if [ $# -gt 0 ]; then
    exec "$@"
else
    echo "Starting Supervisor..."
    exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
