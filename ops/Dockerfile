#################
### base-app
#################
FROM php:8.1.0-fpm-alpine AS base-app

ENV SECURITY_CHECKER_VER 1.2.0
ENV APCU_VERSION 5.1.21

RUN set -xe \
    \
    && echo "****** Install system dependencies ******" \
    && apk add --no-cache --progress icu-dev postgresql-dev \
    && apk add --no-cache --progress --virtual .build-deps $PHPIZE_DEPS \
    \
    && echo "****** Install PHP modules ******" \
    && NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${NPROC} intl opcache pdo pdo_pgsql \
    && pecl install apcu-${APCU_VERSION} \
    && docker-php-ext-enable --ini-name 20-apcu.ini apcu \
    \
    && echo "****** Remove unused system librabies ******" \
    && apk del --progress .build-deps \
    \
    && echo "****** Add security checker ******" \
    && curl -sLo /usr/local/bin/local-php-security-checker \
       https://github.com/fabpot/local-php-security-checker/releases/download/v${SECURITY_CHECKER_VER}/local-php-security-checker_${SECURITY_CHECKER_VER}_linux_amd64 \
    && chmod +x /usr/local/bin/local-php-security-checker


########################
### composer
########################
FROM base-app AS composer

ENV ZIP_VERSION 1.19.4

RUN set -xe \
    \
    && echo "****** Install composer dependencies ******" \
    && apk add --no-cache --progress git libzip-dev \
    && apk add --no-cache --progress --virtual .build-deps $PHPIZE_DEPS \
    \
    && echo "****** Install PHP modules ******" \
    && pecl install zip-${ZIP_VERSION} \
    && docker-php-ext-enable --ini-name 20-zip.ini zip \
    \
    && echo "****** Remove unused system librabies ******" \
    && apk del --progress .build-deps

ENV COMPOSER_ALLOW_SUPERUSER 0
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 2.2.6

RUN set -xe \
    \
    && echo "****** Install composer ******" \
    && EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)" \
    && php -d "memory_limit=1G" -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" \
    && if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then \
         >&2 echo 'ERROR: Invalid installer signature'; \
         rm composer-setup.php; \
         exit 1; \
       fi \
    && php -d "memory_limit=1G" composer-setup.php --version=${COMPOSER_VERSION} --install-dir=/usr/local/bin --filename=composer \
    && RESULT=$? \
    && rm composer-setup.php \
    && exit $RESULT

WORKDIR /app

USER 1000:1000

####################
### application
####################
FROM base-app AS application

COPY --from=composer /usr/local/bin/composer /usr/local/bin/composer

COPY ops/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-php-fpm.conf
COPY ops/php/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /app

USER www-data:www-data


