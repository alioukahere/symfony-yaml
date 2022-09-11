FROM php:8.1-fpm as php

RUN apt update \
  && apt install -y libzip-dev libxslt-dev \
  && pecl install apcu \
  && docker-php-ext-install pdo_mysql zip xsl intl \
  && docker-php-ext-enable apcu

WORKDIR /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
