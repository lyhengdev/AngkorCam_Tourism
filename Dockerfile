FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/angkorcam.conf \
    && a2enconf angkorcam

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage

COPY scripts/render-start.sh /usr/local/bin/render-start
RUN chmod +x /usr/local/bin/render-start

CMD ["render-start"]
