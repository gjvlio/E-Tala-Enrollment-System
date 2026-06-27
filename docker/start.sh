#!/usr/bin/env bash
set -e

# Cache config/routes/views for production performance.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# DESTRUCTIVE one-time reset: drop every table and rebuild from scratch + seed.
# Set FRESH_ON_DEPLOY=true in Render env, deploy once, then set it back to false.
# Wipes ALL production data — use only when you intend a clean slate.
if [ "${FRESH_ON_DEPLOY}" = "true" ]; then
    php artisan migrate:fresh --force --seed
else
    # Apply pending schema changes (safe to run every deploy).
    php artisan migrate --force

    # Seed once on the first deploy. Set SEED_ON_DEPLOY=true, deploy, then false
    # so later deploys don't duplicate data.
    if [ "${SEED_ON_DEPLOY}" = "true" ]; then
        php artisan db:seed --force
    fi
fi

# Bind the built-in server to the port Render provides.
php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
