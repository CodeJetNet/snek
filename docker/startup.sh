#!/usr/bin/env bash
php-fpm -D

# Wait until PHP-FPM is ready
while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done;
nginx
