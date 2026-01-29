FROM php:8.3-apache

# Apache rewrite (handig als je routes via index.php doet)
RUN a2enmod rewrite

# PHP extensions die je vaak nodig hebt voor MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Zet document root naar /public als je die hebt
# Als je backend geen /public heeft, comment deze 2 regels uit.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
  /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copy (voor production builds; tijdens dev overschreven door volume)
COPY . /var/www/html
