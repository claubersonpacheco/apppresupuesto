# Usa a imagem oficial do PHP com extensões necessárias
FROM php:8.2-fpm

# Instala pacotes necessários
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql bcmath

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos da aplicação
COPY . .

# Instala as dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Gera a chave da aplicação
RUN php artisan key:generate

# Define permissões para storage e cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Define a porta que o container vai expor
EXPOSE 9000

# Comando para rodar o PHP-FPM
CMD ["php-fpm"]
