FROM php:7.1.3

RUN apt-get update && apt-get install -y git zip
RUN docker-php-ext-install pdo pdo_mysql

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN curl --silent --show-error https://getcomposer.org/installer | php 

RUN mv composer.phar /usr/local/bin/composer

RUN composer global require hirak/prestissimo --no-plugins --no-scripts

WORKDIR /package

COPY composer.json .

RUN composer update --prefer-lowest --no-autoloader --no-scripts
RUN composer install --no-scripts --no-autoloader

COPY . ./

RUN composer dump-autoload

RUN vendor/bin/phpunit
