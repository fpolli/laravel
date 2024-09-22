FROM dunglas/frankenphp

RUN install-php-extensions \
  intl \
  pcntl \
  pgsql \
  pdo_pgsql \
  pdo_sqlite

RUN apt-get update && apt-get install -y libmemcached-dev libssl-dev zlib1g-dev \
  && pecl install memcached-3.2.0 \
  && docker-php-ext-enable memcached

RUN apt-get install -y \
  libzip-dev \
  && docker-php-ext-install zip

RUN apt-get install libnss3-tools -y

COPY php.ini /usr/local/etc/php

RUN apt-get install -y git

COPY . /app

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php

RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN curl -fsSL https://deb.nodesource.com/setup_current.x | bash - && \
  apt-get install -y nodejs \
  build-essential && \
  node --version && \ 
  npm --version

RUN apt-get install -y ffmpeg

RUN composer self-update

#RUN composer create-project laravel/laravel sobb

#RUN composer require laravel/octane

#RUN php artisan octane:install

#ENTRYPOINT ["php", "artisan", "octane:frankenphp"]