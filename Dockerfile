# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

# "php" stage
FROM php:8.1-fpm-alpine

# persistent / runtime deps
RUN apk add --no-cache \
        gcompat \
        acl \
		fcgi \
		file \
		gettext \
		git \
		gnu-libiconv \
        php-pear \
        autoconf \
        gcc \
        make \
        g++ \
        zlib-dev \
    ;

# install gnu-libiconv and set LD_PRELOAD env to make iconv work fully on Alpine image.
# see https://github.com/docker-library/php/issues/240#issuecomment-763112749
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so
ARG APCU_VERSION=5.1.21

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		postgresql-dev \
		rabbitmq-c-dev \
    ; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		zip \
		bcmath \
		sockets \
        pdo \
        pdo_mysql \
        pdo_pgsql \
    ; \
	pecl install \
		apcu-${APCU_VERSION} \
        amqp \
        xdebug \
    ; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
        amqp \
        xdebug \
    ; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# build for production
ARG APP_ENV=dev

WORKDIR /uello

ENV SYMFONY_PHPUNIT_VERSION=9
CMD ["php-fpm"]

