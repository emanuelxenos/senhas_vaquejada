FROM php:8.2-fpm

# Definir diretório de trabalho
WORKDIR /var/www

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar e instalar extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd zip opcache

# Obter o Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar o restante do código da aplicação
COPY . /var/www

# Ajustar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expor a porta 9000 para comunicação FastCGI
EXPOSE 9000

# Script de entrada para automação
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
