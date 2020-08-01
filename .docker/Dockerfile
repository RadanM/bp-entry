FROM php:7.4-apache

RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli