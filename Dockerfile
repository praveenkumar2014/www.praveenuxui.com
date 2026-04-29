# Dockerfile for Praveen Kumar K Portfolio (Coolify / Self-Hosted)
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required PHP extensions if any (optional for basic portfolio)
# RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# The default entrypoint for php:apache is apache2-foreground
