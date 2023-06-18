FROM php:8.1.4-fpm

MAINTAINER Vitalii Malinovskyi <malina.mvb@gmail.com>

RUN apt-get update \
    && apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev \
        curl \
        git \
        zip \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libpq-dev \
        libxml2-dev \
        libmagickwand-dev \
        libonig-dev \
        supervisor \
        postgresql \
        postgresql-contrib \
        cron \
        wget \
        ca-certificates \
        gnupg2 \
        vim \
    && wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add - \
    && apt-get -y install lsb-release \
    && sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list' \
    && apt-get update \
    && apt-get install -y postgresql \
    && pecl install mongodb \
    && pecl install imagick \
    && docker-php-ext-install intl opcache shmop zip pdo pgsql pdo_pgsql exif mbstring xml gd bcmath \
    && rm -rf /var/lib/apt/lists/* \
    && curl -s https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && alias codecept="/var/www/vendor/bin/codecept" \
    && apt-get clean

WORKDIR /var/www

CMD supervisord -c /etc/supervisor/supervisord.conf && php-fpm