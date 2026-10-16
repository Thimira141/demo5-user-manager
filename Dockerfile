# Use PHP 8.4 FPM as base
FROM php:8.4-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libsqlite3-dev libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Mark project folder safe for Git (fixes dubious ownership error)
RUN git config --global --add safe.directory /var/www/html

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node.js + npm for Vite frontend build
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install && npm run build

# Expose PHP-FPM port
EXPOSE 9000

# Default command
CMD ["php-fpm"]
