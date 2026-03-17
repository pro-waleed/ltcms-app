FROM php:8.4-cli

# تثبيت الأدوات والامتدادات المطلوبة
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

# تثبيت Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# تحديد مجلد العمل
WORKDIR /app

# نسخ ملفات المشروع
COPY . .

# تثبيت الاعتمادات
RUN composer install --no-dev --optimize-autoloader --no-interaction

# تجهيز Laravel
RUN if [ -f .env.example ]; then cp .env.example .env; fi
RUN php artisan key:generate || true
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

# فتح المنفذ
EXPOSE 10000

# تشغيل التطبيق
CMD php artisan serve --host=0.0.0.0 --port=10000
