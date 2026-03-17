FROM php:8.2-cli

# تثبيت الأدوات المطلوبة
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip

# تثبيت composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تحديد مجلد العمل
WORKDIR /app

# نسخ الملفات
COPY . .

# تثبيت Laravel
RUN composer install --no-dev --optimize-autoloader

# فتح البورت
EXPOSE 10000

# تشغيل السيرفر
CMD php artisan serve --host=0.0.0.0 --port=10000