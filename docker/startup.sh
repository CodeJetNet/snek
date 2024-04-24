#!/usr/bin/env bash
php-fpm -D

# Because cloud run wants to decide what port to run on.
sed -i "s/80/$PORT/g" /etc/nginx/nginx.conf

# Wait until PHP-FPM is ready
while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done;
nginx
