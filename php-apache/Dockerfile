FROM php:8.2-apache
# Postgres ile konuşabilmesi için gerekli eklentiyi kuruyoruz
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql
