FROM php:7.0.3-fpm

RUN apt-get update && apt-get -y install libmagickwand-dev \
&& pecl install imagick && docker-php-ext-enable imagick && rm -r /var/lib/apt/lists/*

WORKDIR /var/www

COPY index.php /var/www

CMD [ "php", "./index.php" ]