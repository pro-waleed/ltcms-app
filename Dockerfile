FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN if [ -f .env.example ]; then cp .env.example .env; fi
RUN mkdir -p database && touch database/database.sqlite
RUN sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=sqlite|' .env || true
RUN sed -i 's|^DB_DATABASE=.*|DB_DATABASE=/app/database/database.sqlite|' .env || true
RUN sed -i 's|^SESSION_DRIVER=.*|SESSION_DRIVER=file|' .env || true
RUN sed -i 's|^CACHE_STORE=.*|CACHE_STORE=file|' .env || true
RUN sed -i 's|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=sync|' .env || true
RUN php artisan key:generate || true
RUN php artisan migrate --force || true

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
