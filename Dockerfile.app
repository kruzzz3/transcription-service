# Specifies the base image, use the PHP version 8.1.5.
# And also use node as a second base image.
FROM node:latest AS node
FROM php:8.1.5-fpm-buster

ADD ./docker/configs/php.ini /usr/local/etc/php

# Update apt-get.
RUN apt-get update

# Sort of installing npm inside our image, but it's actually just copying its binaries from the standard node image available at Docker Hub.
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# Install the missing PHP extension Laravel needs.
# Tip: Check what is already installed
# docker run php:8.1.5-fpm-buster php -m
RUN docker-php-ext-install bcmath pdo_mysql fileinfo

# Install some tools composer will need to do its job.
RUN apt-get install -y git zip unzip

# Sort of installing composer inside our image, but it's actually just copying its binaries from the standard Composer image available at Docker Hub.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install ffmpeg for audio splitting
RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get install -y ffmpeg

# Install zip
RUN apt-get update && apt-get install -y libzip-dev && docker-php-ext-install zip

# Disable access log
RUN echo "access.log = /dev/null" >> /usr/local/etc/php-fpm.d/www.conf

# Link storage
CMD ["php", "artisan", "storage:link"]

# Install & activate opcache for better "time to first byte"
RUN docker-php-ext-install opcache

# Tell docker to expose the container's port 9000.
EXPOSE 9000

# Copy entrypoint script make it executable and run it
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
