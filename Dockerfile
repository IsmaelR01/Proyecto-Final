FROM php:apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite en Apache
RUN a2enmod rewrite

# Configurar PHP para mostrar errores
RUN echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/errors.ini