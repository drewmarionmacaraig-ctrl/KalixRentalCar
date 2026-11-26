FROM php:8.3-cli-bookworm

# System dependencies & PHP extensions (MySQL, DomPDF GD/XML/MBString, Zip)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies first for layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Install Node dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy application source and build frontend assets
COPY . .
RUN npm run build \
    && rm -rf node_modules \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/uploads \
    && chmod -R 775 storage bootstrap/cache public/uploads \
    && php artisan package:discover --ansi \
    && chmod +x /app/docker/start.sh \
    && sed -i 's/\r$//' /app/docker/start.sh

EXPOSE 8080

CMD ["/app/docker/start.sh"]
