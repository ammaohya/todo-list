FROM php:7.2.34-fpm

COPY config/php.ini /usr/local/etc/php/

## see https://github.com/docker-library/php/issues/307
RUN curl -sS -o /tmp/icu.tar.gz -L https://github.com/unicode-org/icu/releases/download/release-67-1/icu4c-67_1-src.tgz && \
    tar -zxf /tmp/icu.tar.gz -C /tmp && \
    cd /tmp/icu/source && \
    ./configure --prefix=/usr/local && \
    make && \
    make install
RUN docker-php-ext-configure intl --with-icu-dir=/usr/local && \
    docker-php-ext-install intl bcmath

RUN docker-php-ext-enable opcache.so

RUN apt-get update && \
    apt-get install -y \
    iputils-ping \
    vim \
    acl \
    procps \
    make \
    autoconf \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    tar \
    unzip \
    zlib1g-dev \
    wget && \
    docker-php-ext-install pdo_mysql \
    iconv \
    mbstring \
    zip \
    gd

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --version=2.5.7 --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

RUN set -ex && \
    cd $SOURCE_DIR && \
    curl -LsS https://pecl.php.net/get/xdebug-2.9.2.tgz | tar zx && \
    cd xdebug-2.9.2 && \
    phpize && \
    ./configure && \
    make && \
    make install

WORKDIR /var/www