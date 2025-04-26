# Use official PHP 8.1 with Apache
FROM php:8.1-apache

# تثبيت امتدادات PHP المطلوبة
RUN docker-php-ext-install curl mbstring

# انسخ جميع ملفات المشروع إلى مجلد الويب
COPY . /var/www/html/

# اجعل مجلد الـ storage قابلًا للكتابة
RUN chmod -R 777 /var/www/html/storage

# (اختياري) إذا أردت تخصيص إعدادات Apache:
# RUN a2enmod rewrite
# COPY ./apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Render يكتشف تلقائيًّا المنفذ الذي يستمع عليه Apache