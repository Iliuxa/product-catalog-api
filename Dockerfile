FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    unzip \
    libmariadb-dev \
    libonig-dev \
    zip \
    curl \
    git \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install pdo pdo_mysql mbstring pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
