FROM php:8.1-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/web/apache.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www

WORKDIR /var/www

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www

EXPOSE 80

CMD ["apache2-foreground"]
