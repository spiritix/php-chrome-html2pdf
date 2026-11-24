FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
  git unzip \
  ca-certificates fonts-liberation libasound2 libatk-bridge2.0-0 libatk1.0-0 libc6 \
  libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libglib2.0-0 libgtk-3-0 libnspr4 \
  libnss3 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 \
  libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 lsb-release \
  wget xdg-utils \
  libzip-dev \
  && docker-php-ext-install zip \
  && pecl install xdebug-3.3.1 && docker-php-ext-enable xdebug \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@12.1

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./php.ini /usr/local/etc/php/conf.d/40-custom.ini

Variables for working with puppeteer
ENV PUPPETEER_CACHE_DIR=/home/www-data/.cache/puppeteer
ENV XDG_CONFIG_HOME=/tmp/.chromium
ENV XDG_CACHE_HOME=/tmp/.chromium

# Changing the user's UID and working under www-data
ENV HOME=/home/www-data
RUN usermod -u 1000 www-data && chown -R www-data:www-data /var/www/html \
        && mkdir -p /home/www-data && chown -R www-data:www-data /home/www-data
USER www-data

CMD ["php", "-a"]
