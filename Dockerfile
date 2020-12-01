# Utility image to rn tests & stuff
FROM php:8.0.0RC5-fpm-alpine
COPY --from=composer:2.0 /usr/bin/composer /usr/bin
COPY composer.* ./
#RUN composer install && composer check-platform-reqs

