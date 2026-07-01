FROM php:8.2-apache

RUN a2enmod rewrite \
    && apt-get update \
    && apt-get install -y unzip git curl \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install --no-dev --prefer-dist --optimize-autoloader \
    && sed -ri 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && printf "<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n" > /etc/apache2/conf-available/aijiu-public.conf \
    && a2enconf aijiu-public

EXPOSE 80
CMD ["apache2-foreground"]
