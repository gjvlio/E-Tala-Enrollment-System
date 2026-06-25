# syntax=docker/dockerfile:1

# ── Stage 1: build front-end assets (Vite/Bootstrap) ──────────────────────────
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# ── Stage 2: install PHP dependencies ─────────────────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# ── Stage 3: runtime ──────────────────────────────────────────────────────────
FROM php:8.3-cli AS runtime

# System libs + PHP extensions Laravel + Postgres need.
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# App source, with vendor + built assets from earlier stages.
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Writable runtime dirs.
RUN chmod -R 775 storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Render injects $PORT; the start script binds to it.
CMD ["start.sh"]
