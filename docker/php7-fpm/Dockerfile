FROM php:7.3-fpm
LABEL maintainer="olivier.bacs@outlook.com"

ARG TIMEZONE

RUN apt-get update && apt-get install --no-install-recommends -qy \
    jq \
    openssl \
    unzip \
    gnupg \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    libzip-dev \
    zip

# Install Yarn then Nodejs (Only for local or during build)
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb http://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get update && apt-get install --no-install-recommends -qy \
    yarn \
    && rm -rf /var/lib/apt/lists/*

# ==== Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
# ====

# ==== Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone
RUN printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini
# ====

RUN pecl install zip

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo_mysql opcache
RUN docker-php-ext-configure zip --with-libzip

ADD conf.d/* /usr/local/etc/php/conf.d/

RUN pecl install -o -f redis \
  &&  rm -rf /tmp/pear \
  &&  docker-php-ext-enable redis

WORKDIR /var/www/todo