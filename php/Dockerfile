# Usar a imagem oficial do PHP com FPM
FROM php:8.1-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip \
    libssl-dev \
    && pecl install apcu redis \
    && docker-php-ext-enable apcu redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Habilitar manualmente as extensões no php.ini
RUN echo "extension=apcu.so" >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini \
    && echo "extension=redis.so" >> /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# Instalar o Composer (gerenciador de dependências do PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar o código da aplicação
COPY ./app /var/www/html

# Definir permissões para o diretório
RUN chown -R www-data:www-data /var/www/html

# Definir o diretório de trabalho
WORKDIR /var/www/html
