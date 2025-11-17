# Dockerfile - PHP 8.2 + Apache + pgsql
FROM php:8.2-apache

# instalar extensiones necesarias
RUN apt-get update && apt-get install -y libpq-dev git zip unzip \
  && docker-php-ext-install pdo pdo_pgsql pgsql \
  && a2enmod rewrite

# copiar el api al document root
COPY api/ /var/www/html/

# permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer puerto 80
EXPOSE 80
