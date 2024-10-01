ARG NODE_VERSION=22
ARG PHP_VERSION=8.3

FROM node:${NODE_VERSION}-bullseye AS node
FROM php:${PHP_VERSION}-fpm-bullseye

# Install services
RUN apt-get update -y \
    && apt-get install -y nginx supervisor zip unzip

# Install libraries for PHP extensions
RUN apt-get install -y \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libsodium-dev \
    libexif-dev \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libxml2-dev \
    libonig-dev \
    libpng-dev \
    cron

# Install PHP extensions
RUN docker-php-ext-configure zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip intl sodium exif pcntl bcmath mbstring \
    && docker-php-ext-install -j$(nproc) gd

# Install redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Setup nodejs
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# Copy php configs
ADD .docker-local/conf/backend/conf.d/php.ini /usr/local/etc/php/php.ini

# Copy nginx config
COPY .docker-local/conf/backend/nginx-site.conf /etc/nginx/sites-enabled/default

# Copy supervisor config
COPY .docker-local/conf/backend/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy entrypoint
COPY .docker-local/conf/backend/entrypoint.sh /scripts/entrypoint.sh

# Setup the cron job
COPY .docker-local/conf/backend/cronjob /etc/cron.d/cronjob
RUN chmod 0644 /etc/cron.d/cronjob
RUN crontab /etc/cron.d/cronjob
RUN touch /var/log/cron.log

RUN chmod +x /scripts/entrypoint.sh

# xdebug
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
RUN install-php-extensions xdebug
ENV PHP_IDE_CONFIG 'serverName=localhost'
RUN echo "xdebug.mode=develop,debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request = trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www

COPY --chown=www-data:www-data . .

RUN echo 'alias a="php artisan"' >> ~/.bashrc
RUN chmod -R 777 ./bootstrap/cache ./storage
RUN composer install --optimize-autoloader

EXPOSE 80

ENTRYPOINT ["/scripts/entrypoint.sh"]
