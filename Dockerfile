FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxpm-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install gd && \
    apt-get clean && rm -rf /var/lib/apt/lists/*


RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN sed -i 's/80/8081/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:8081/' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/


RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html


EXPOSE 8081

CMD ["apache2ctl", "-D", "FOREGROUND"]
