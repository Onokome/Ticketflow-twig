FROM php:8.1-cli

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . .

# Create data directory if it doesn't exist and set permissions
RUN mkdir -p data && chmod -R 755 data

# Set permissions for the entire app
RUN chmod -R 755 .

# Expose port 8000
EXPOSE 8000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]