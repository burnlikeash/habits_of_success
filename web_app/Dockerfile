FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /code/app
COPY ./ /code/app
RUN composer install

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
