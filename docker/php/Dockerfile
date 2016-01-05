FROM php:7.0-cli
#MAINTAINER Mateusz Tymek <mtymek@gmail.com>

# Install sockets extension
RUN docker-php-ext-install sockets
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Start server
CMD env php /var/www/html/bin/server.php
