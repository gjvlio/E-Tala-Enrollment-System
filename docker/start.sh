#!/usr/bin/env bash
set -e

# Cache config/routes/views for production performance.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Apply database schema (safe to run every deploy).
php artisan migrate --force

# Seed once on the first deploy. Set SEED_ON_DEPLOY=true in Render env, deploy,
# then set it back to false so later deploys don't duplicate data.
if [ "${SEED_ON_DEPLOY}" = "true" ]; then
    php artisan db:seed --force
fi

# Bind the built-in server to the port Render provides.
php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
