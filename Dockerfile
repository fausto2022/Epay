FROM php:8.2-fpm

# Install system dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    libgmp-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        gmp \
        gd \
        curl \
        zip \
        bcmath \
    && rm -rf /var/lib/apt/lists/*

# PHP production tuning
RUN echo "memory_limit=256M" > /usr/local/etc/php/conf.d/epay.ini \
    && echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/epay.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/epay.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/epay.ini \
    && echo "date.timezone=Asia/Shanghai" >> /usr/local/etc/php/conf.d/epay.ini

# Entrypoint: fix file ownership at runtime for volume-mounted directory
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
