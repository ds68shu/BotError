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

# 4) أذونات الكتابة لمجلد التخزين
RUN chmod -R 777 /var/www/html/storage
