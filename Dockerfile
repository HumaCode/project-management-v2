FROM dunglas/frankenphp:1-php8.4

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    pcntl \
    gd \
    intl \
    pdo_mysql \
    zip \
    opcache \
    bcmath \
    exif

# Set Caddy server name
ENV SERVER_NAME=":80"

# Set working directory
WORKDIR /app

# Copy the application code
COPY . /app

# Set permissions for entrypoint and storage
RUN chmod +x /app/docker-entrypoint.sh \
    && chmod -R 777 storage bootstrap/cache

# Performance: Use worker mode
ENV OCTANE_SERVER=frankenphp

# Expose port
EXPOSE 80 443 443/udp

ENTRYPOINT ["/app/docker-entrypoint.sh"]

# Entrypoint to start Laravel Octane with FrankenPHP
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--admin-port=2019"]
