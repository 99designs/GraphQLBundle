FROM php:8.3
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apt update
RUN apt install git -y
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip
RUN pecl install xdebug && docker-php-ext-enable xdebug
WORKDIR /var/www/html
ENTRYPOINT ["tail", "-f", "/dev/null"]
