FROM php:7.4-apache
RUN docker-php-ext-install mysqli
COPY . /var/www/html/
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
EXPOSE 80
