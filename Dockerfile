FROM php:8.2-apache

# Disable conflicting MPMs, keep prefork
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Enable rewrite (optional but useful)
RUN a2enmod rewrite

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy app
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
