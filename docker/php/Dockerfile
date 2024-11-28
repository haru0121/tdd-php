FROM php:8.0-apache
COPY php.ini /usr/local/etc/php/
COPY *.conf /etc/apache2/sites-enabled/
WORKDIR /var/www/html

RUN apt-get update \
	&& apt-get install -y libzip-dev unzip \
	&& docker-php-ext-install zip pdo_mysql mysqli \
	&& docker-php-ext-enable mysqli \
	&& a2enmod rewrite \
	&& pecl install xdebug \
	&& docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer