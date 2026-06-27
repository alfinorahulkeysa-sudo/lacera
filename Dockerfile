# --- Stage 1: Build Frontend Assets ---
FROM node:18-alpine AS node-builder
WORKDIR /app
COPY package*.json vite.config.js postcss.config.js tailwind.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm ci && npm run build

# --- Stage 2: Main Application ---
FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

# Install system dependencies (including gettext for envsubst)
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    gettext

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql bcmath zip opcache pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer dependency definitions
COPY composer.json composer.lock ./

# Install php dependencies without running scripts
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy the rest of the application files
COPY . .

# Copy compiled assets from node-builder stage
COPY --from=node-builder /app/public/build ./public/build

# Finish composer setup
RUN composer dump-autoload --no-dev --optimize

# Create directories and set correct permissions for Laravel storage & cache
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copy Nginx config as a TEMPLATE (PORT will be substituted at runtime by entrypoint)
COPY docker/nginx.conf /etc/nginx/http.d/default.conf.template

# Copy Supervisor and Entrypoint configurations
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Fix line endings of entrypoint script and make it executable
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Render uses PORT env var (default 10000)
EXPOSE 10000

# Run entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
