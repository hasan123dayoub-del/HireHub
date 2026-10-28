# Demo deployment image: Laravel API on SQLite, seeded with demo data on every boot.
# CACHE_STORE=array: the app uses cache tags (redis, memcached or array only) and the free tier has no Redis.

# 1) PHP dependencies (needed before the asset build, which imports Flux CSS from vendor/)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs

# 2) Frontend assets (Vite)
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# 3) Runtime
FROM php:8.3-cli
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev --no-interaction \
    && chmod +x docker/start.sh

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    DB_CONNECTION=sqlite \
    QUEUE_CONNECTION=sync \
    CACHE_STORE=array \
    SESSION_DRIVER=database \
    PHP_CLI_SERVER_WORKERS=4

EXPOSE 8000
CMD ["docker/start.sh"]
