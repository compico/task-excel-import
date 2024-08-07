FROM php:8.2-fpm-alpine

RUN apk add \
    git \
    curl \
    zip \
    make \
    unzip \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    autoconf \
    build-base \
    linux-headers \
    libmemcached-dev \
    zlib-dev \
    libressl-dev

# install mysql dependency
RUN docker-php-ext-install mysqli pdo pdo_mysql gd sockets zip

# add mmecached
RUN git clone https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached \
  && cd /usr/src/php/ext/memcached \
  && phpize && ./configure && make \
  && docker-php-ext-configure memcached \
  && docker-php-ext-install memcached

# add composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
