FROM php:8.2-fpm

# Argumentos para configuração
ARG user=www-data
ARG uid=1000

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libmagickwand-dev \
    imagemagick \
    supervisor \
    cron \
    nano \
    && rm -rf /var/lib/apt/lists/*

# Instalar Node.js + npm (versão 18.x LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    node -v && npm -v

# Instalar extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Instalar Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Imagick extension
RUN pecl install imagick && docker-php-ext-enable imagick

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar diretório da aplicação
WORKDIR /var/www/html

# Copiar arquivos de configuração do PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copiar arquivos da aplicação
COPY . /var/www/html

# Definir permissões corretas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# O composer install será executado pelo script de setup

# Configurar supervisor para queue workers
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expor porta 9000 para PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"] 