FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
  git \
  nodejs \
  npm

RUN curl -fsSL https://deb.nodesource.com/setup_current.x | bash - && \
 apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/app
WORKDIR /usr/src/app

CMD ["php", "-a"]