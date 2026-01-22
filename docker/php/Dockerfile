
FROM ubuntu:20.04

RUN apt-get update

FROM php:8.3-fpm

# install AMQP para Messenger/RabbitMQ
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev librabbitmq-dev \
 && docker-php-ext-install pdo_mysql intl zip \
 && pecl install amqp \
 && docker-php-ext-enable amqp

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Opcional: usuario no-root
RUN useradd -m appuser
USER appuser
