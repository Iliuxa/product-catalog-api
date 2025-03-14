# Используем официальный PHP 8.4 с Apache
FROM php:8.4-apache

# Устанавливаем необходимые расширения PHP и зависимости
RUN apt-get update && apt-get install -y \
    unzip \
    libmariadb-dev \
    libonig-dev \
    zip \
    curl \
    git \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install pdo pdo_mysql mbstring pcntl

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости проекта через Composer
# RUN composer install --no-dev --optimize-autoloader

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

# Открываем порт
EXPOSE 80

# Запускаем Apache
CMD ["apache2-foreground"]
