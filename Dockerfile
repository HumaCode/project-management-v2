FROM dunglas/frankenphp:1-php8.4

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
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

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Performance: Use worker mode
ENV OCTANE_SERVER=frankenphp

# Expose port
EXPOSE 80 443 443/udp

# Entrypoint to start Laravel Octane with FrankenPHP
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--admin-port=2019"]
