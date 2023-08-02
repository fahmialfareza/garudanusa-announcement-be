# Use the official PHP image as the base image with Alpine Linux
FROM php:8.2-cli-alpine

# Install system dependencies
RUN apk update && apk add --no-cache \
  libzip-dev \
  zip \
  unzip \
  libsodium-dev \
  libpng-dev \
  freetype-dev \
  libjpeg-turbo-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip sodium
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the Lumen application files to the container
COPY . /var/www/html

# Install Composer (skip if you have installed Composer globally)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies using Composer
RUN composer install --no-interaction --no-dev --prefer-dist

# Expose the port on which the Lumen app will run
EXPOSE 8000

# Start the Lumen application using the built-in PHP web server
CMD ["php", "-S", "0.0.0.0:8000"]
