FROM php:8.2-cli

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /app

# Copy project
COPY . .

# Start PHP server
CMD php -S 0.0.0.0:$PORT
