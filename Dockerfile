FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Permissions (recommended)
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80
