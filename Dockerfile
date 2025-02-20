# Usa a imagem oficial do PHP com extensões necessárias
FROM php:8.2-fpm

# Instala o NGINX e pacotes necessários
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql bcmath intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /app

# Copia os arquivos da aplicação
COPY . .

# Copia o arquivo de configuração do NGINX
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Ajusta permissões para o Laravel funcionar corretamente
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# Instala as dependências do Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Gera a chave da aplicação
RUN php artisan key:generate

# Expor a porta 80 para o NGINX
EXPOSE 80

# Comando para rodar o PHP-FPM e NGINX juntos
CMD service nginx start && php-fpm
