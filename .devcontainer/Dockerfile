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

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]