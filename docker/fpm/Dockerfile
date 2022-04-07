FROM php:7.2-fpm-alpine3.6

RUN mkdir -p /var/www/html/public/ \
    && set -ex \
  	&& apk update \
  	&& apk upgrade \
    && apk add --no-cache git mysql-client curl openssh-client icu libpng libjpeg-turbo busybox fcgi \
    && apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev imap-dev openssl-dev g++ make autoconf \
    && docker-php-source extract \
    && pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && docker-php-source delete \
    && docker-php-ext-install pdo pdo_mysql intl zip gd mbstring sockets opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && cd  / && rm -fr /src \
    && apk del build-dependencies \
    && rm -rf /tmp/* \
    && wget -O /usr/local/bin/php-fpm-healthcheck \
       https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck


RUN apk add --no-cache icu-dev imap c-client libssl1.0 libressl-dev imap-dev \
    && docker-php-ext-configure imap --with-imap-ssl \
    && docker-php-ext-install imap \
    && apk del --no-cache icu-dev libressl-dev imap-dev

RUN mkdir -p /var/www/html/var/cache/ \
    && chmod -R 777 /var/www/html/var/cache/

RUN sed -i 's/pm.max_children = 5/pm.max_children = 20/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/pm.start_servers = 2/pm.start_servers = 8/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/pm.max_spare_servers = 3/pm.max_spare_servers = 10/g' /usr/local/etc/php-fpm.d/www.conf

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug-dev.ini
COPY php.ini /usr/local/etc/php/php.ini
COPY zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

USER www-data

WORKDIR /var/www/html
CMD ["php-fpm"]