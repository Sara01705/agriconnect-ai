FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    python3 \
    python3-pip \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN pip3 install -r requirements.txt

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install && npm run build

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}