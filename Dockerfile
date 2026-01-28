FROM php:8.4-cli

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apt-get update
COPY docker-install-tools.sh /usr/local/bin/
RUN docker-install-tools.sh
WORKDIR /www
