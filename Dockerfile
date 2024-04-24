FROM php:8.2-fpm-alpine3.18

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apk update \
    && apk add --no-cache curl bash nginx \
    && rm -rf /var/cache/apk/*

RUN mkdir -p /run/nginx
RUN mkdir -p /run/php

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/startup.sh /usr/local/bin/startup.sh

WORKDIR /app
COPY . /app

# This is useless when run local
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install --optimize-autoloader --no-dev
RUN rm -rf composer.phar

RUN chown -R www-data: /app

EXPOSE 80

CMD ["/usr/local/bin/startup.sh"]
