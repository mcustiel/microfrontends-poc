FROM php:7.3-fpm

RUN apt-get update && apt-get -y install git gzip wget zlib1g-dev zip bzip2 libbz2-dev libmcrypt-dev sqlite3 libsqlite3-dev libzip-dev
RUN docker-php-ext-install zip bz2 pdo_sqlite

# Setup composer
RUN EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)" && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" && \
    if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ] ; then >&2 echo 'ERROR: Invalid installer signature'; rm composer-setup.php; exit 1; fi && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    RESULT=$? && \
    rm composer-setup.php

RUN chmod +x /usr/local/bin/composer

RUN pecl install apcu-5.1.17 \
	&& docker-php-ext-enable apcu

ENV XDEBUG_PORT 9000
ENV XDEBUG_CONFIG "remote_host=localhost remote_connect_back=1"
ENV PHP_IDE_CONFIG "serverName=Docker"

#ARG DEBUG_IDEKEY=PHPSTORM
#RUN pecl install xdebug-2.6.1
#RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo "xdebug.idekey=\"$DEBUG_IDEKEY\"" >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo 'xdebug.remote_handler=dbgp' >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo 'xdebug.profiler_enable=0' >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo 'xdebug.profiler_output_dir="/var/www/html/"' >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo 'xdebug.cli_color=1' >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& echo 'xdebug.var_display_max_depth=10' >> /usr/local/etc/php/conf.d/xdebug.ini \
#	&& docker-php-ext-enable xdebug

COPY ./opcache.ini $PHP_INI_DIR/conf.d/ 
RUN docker-php-ext-enable opcache

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
