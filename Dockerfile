# Use official PHP 8.1 with Apache
FROM php:8.1-apache

# 1) حدِّث apt ثم ثبّت الحزم التطويرية المطلوبة لبناء mbstring وcurl
RUN apt-get update \
    && apt-get install -y \
       libonig-dev \
       libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# 2) ابنِ امتدادات PHP المطلوبة
RUN docker-php-ext-install mbstring curl

# 3) انسخ ملفات المشروع
COPY . /var/www/html/

# 4) أنشئ مجلد storage إذا لم يكن موجوداً ثم اجعله قابلاً للكتابة
RUN mkdir -p /var/www/html/storage \
    && chmod -R 777 /var/www/html/storage

# (اختياري) إذا أردت تخصيص إعدادات Apache:
# RUN a2enmod rewrite
# COPY ./apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Render يكتشف تلقائيًّا المنفذ الذي يستمع عليه Apache ولا حاجة لتعريف Start Command
