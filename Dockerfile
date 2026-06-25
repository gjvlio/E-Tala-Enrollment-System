# syntax=docker/dockerfile:1

# ── Stage 1: build front-end assets (Vite/Bootstrap) ──────────────────────────
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# ── Stage 2: PHP 8.4 runtime + Composer deps ──────────────────────────────────
# Laravel 13 / Symfony 8 require PHP >= 8.4, so build and run on the same 8.4.
FROM php:8.4-cli AS runtime

# System libs + PHP extensions Laravel + Postgres need.
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev libzip-dev unzip git \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer (from the official image).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# App source first, then install PHP deps on 8.4 (artisan scripts run here too).
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Built assets from the node stage.
COPY --from=assets /app/public/build ./public/build

# Writable runtime dirs.
RUN chmod -R 775 storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Render injects $PORT; the start script binds to it.
CMD ["start.sh"]
