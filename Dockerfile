FROM php:apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar mod_rewrite en Apache
RUN a2enmod rewrite


COPY ./mysql-conf.d /etc/mysql/conf.d/

RUN chmod 644 /etc/mysql/conf.d/mysql-conf.cnf


