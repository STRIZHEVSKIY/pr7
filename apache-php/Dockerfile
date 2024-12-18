FROM php:8.1-apache

WORKDIR /var/www/html-dynamic

RUN mkdir "pdf" && mkdir "pdf/files"

# Копирование конфигурации Apache и PHP
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY php.ini /usr/local/etc/php/php.ini
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd
RUN a2enmod rewrite
# Установка необходимых расширений PHP и зависимостей
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-install mysqli zip \
    && pecl install redis \
    && docker-php-ext-enable redis


# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Включение модулей Apache
RUN a2enmod authn_dbd
RUN apt-get install -y apache2-utils libaprutil1-dbd-mysql

# Установка зависимостей через Composer
COPY ./src /var/www/html-dynamic
WORKDIR /var/www/html-dynamic
RUN composer install
