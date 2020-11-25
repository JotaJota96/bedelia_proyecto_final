FROM php:7.4-apache

RUN apt-get update && apt-get install -y
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./ /var/www
COPY ./public /var/www/html

#RUN chmod -R 777 /var/www
RUN chown -R www-data:www-data /var/www

RUN a2enmod rewrite
