FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    gnupg \
    lsb-release \
    apt-transport-https \
    unixodbc-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64,arm64,armhf signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 unixodbc-dev \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo '#!/bin/bash\n\
echo "Checking for Composer dependencies..."\n\
if [ ! -f "vendor/autoload.php" ]; then\n\
    echo "Installing Composer dependencies..."\n\
    composer install --optimize-autoloader --no-interaction --no-progress\n\
fi\n\
\n\
echo "Setting up Laravel environment..."\n\
if [ ! -f ".env" ]; then\n\
    echo "Creating .env file..."\n\
    cp .env.example .env\n\
    php artisan key:generate\n\
fi\n\
\n\
echo "Setting permissions..."\n\
chown -R www-data:www-data storage bootstrap/cache\n\
chmod -R 775 storage bootstrap/cache\n\
\n\
echo "Starting Laravel development server..."\n\
php artisan serve --host=0.0.0.0 --port=8000' > /usr/local/bin/start-laravel.sh

RUN chmod +x /usr/local/bin/start-laravel.sh

EXPOSE 8000

CMD ["/usr/local/bin/start-laravel.sh"]
