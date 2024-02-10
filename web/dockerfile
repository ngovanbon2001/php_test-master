FROM php:8.1.15-apache-buster

# Sao chép các tệp vào container
COPY . /var/www/html

# Thiết lập thư mục làm việc mặc định
WORKDIR /var/www/html

# Cài đặt các gói phụ thuộc
RUN apt-get update && \
    apt-get install -y zip unzip libzip-dev && \
    docker-php-ext-install zip pdo_mysql

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1

# Set chown
RUN chown -R www-data:www-data /var/www/html

# Cấu hình Apache
ADD 000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf
RUN a2enmod rewrite

# Sao chép tệp startup.sh vào container
COPY startup.sh /var/www/html/startup.sh

# Thực thi tệp startup.sh
RUN chmod +x /var/www/html/startup.sh
RUN /var/www/html/startup.sh

CMD apache2-foreground