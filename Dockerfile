FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

#
#1️⃣ تحديث وإضافة حزم النظام
#RUN apt-get update && apt-get install -y \
#    libpng-dev \
#    libjpeg-dev \
#    libfreetype6-dev \
#    zip \
#    git \
#    unzip \
#    curl
#
#
#apt-get update → يحدث قائمة الحزم عشان نقدر نثبت أحدث الإصدارات.
#
#apt-get install -y → تثبيت الحزم التالية:
#
#libpng-dev, libjpeg-dev, libfreetype6-dev → مكتبات للتعامل مع الصور (GD library).
#
#zip, unzip → لضغط وفك ضغط الملفات.
#
#git → للتحكم في النسخ (Clone, Pull).
#
#curl → لتحميل البيانات من الإنترنت أو اختبار السيرفر.
