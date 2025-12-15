# Dockerfile untuk Risk Assessment Objek Wisata
# PHP Application untuk Render.com

FROM php:8.1-apache

# Install system dependencies
RUN apt-get update
RUN apt-get install -y --no-install-recommends libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev libonig-dev unzip git curl
RUN rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd mysqli pdo_mysql zip mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies (skip if vendor already exists)
RUN if [ ! -d "vendor" ]; then \
        composer install --no-dev --optimize-autoloader --no-interaction || true; \
    fi

# Set permissions
RUN chmod -R 755 assets/uploads/ && \
    mkdir -p logs && \
    chmod -R 755 logs/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose port (Railway/Render will use $PORT)
EXPOSE 80
EXPOSE 8080

# Start command (will be overridden by Railway/Render)
# For Railway: php -S 0.0.0.0:$PORT
# For Render with Apache: apache2-foreground
CMD ["apache2-foreground"]

