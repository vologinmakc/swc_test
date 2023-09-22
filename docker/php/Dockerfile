FROM php:8.1-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    libmariadb-dev \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql

# Настройка и установка расширения GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Установка PHP zip extension
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# Установка Git (Нужна для некоторых пакетов)
RUN apt-get update && apt-get install -y git

# Понадобилось для локализации laravel
RUN docker-php-ext-install bcmath

# Установка xDebug и его конфигурация
RUN pecl install xdebug
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
