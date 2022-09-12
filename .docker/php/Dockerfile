FROM php:8.1-fpm as php

RUN apt update \
  && apt install -y libzip-dev libxslt-dev zip \
  && pecl install apcu \
  && docker-php-ext-install pdo_mysql zip xsl intl \
  && docker-php-ext-configure zip \
  && docker-php-ext-enable apcu

WORKDIR /usr/src/app
COPY ./ /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chown www-data:www-data -R data \
  && chmod 775 -R data
