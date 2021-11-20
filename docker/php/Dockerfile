FROM php:8-apache

RUN apt-get update && apt-get install -y vim curl qrencode zbar-tools libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*

RUN printf "\n" | pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY docker/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY ./ /var/www/html/
