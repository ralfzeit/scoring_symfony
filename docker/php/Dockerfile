FROM php:8.2.0-fpm-alpine
RUN apk add --no-cache curl git zlib-dev bash build-base oniguruma-dev autoconf
RUN apk add --update linux-headers
RUN cd /tmp && \
    git clone https://github.com/xdebug/xdebug.git && \
    cd xdebug && \
    git checkout xdebug_3_3 && \
    phpize && \
    ./configure --enable-xdebug && \
    make && \
    make install && \
    rm -rf /tmp/xdebug
RUN docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
WORKDIR /scoring_symfony
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer