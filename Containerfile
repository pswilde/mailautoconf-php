FROM php:7.4-apache

COPY src/ /var/www/html/

COPY ./entrypoint.sh /
RUN chmod +x /entrypoint.sh

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN a2enmod rewrite

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
