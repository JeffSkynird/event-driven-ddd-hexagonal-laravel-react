# Usar una imagen base oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema operativo necesarias para PostgreSQL y otras herramientas
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql sockets

# Habilitar el módulo de reescritura de Apache para Laravel
RUN a2enmod rewrite

# Copiar el archivo de configuración de Apache para apuntar a /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el composer.json y composer.lock
COPY composer.json composer.lock ./

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar las dependencias del proyecto Laravel con Composer
RUN composer install --no-scripts --no-autoloader

# Copiar el resto del código del proyecto Laravel
COPY . .

# Ejecutar autoload y optimizaciones de Composer
RUN composer dump-autoload && composer install

# Crear los directorios necesarios si no existen
RUN mkdir -p /var/www/html/storage/framework/views /var/www/html/storage/framework/cache

# Establecer permisos correctos para las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto 80 para Apache
EXPOSE 80

# Comando para iniciar el servidor Apache
CMD ["apache2-foreground"]
