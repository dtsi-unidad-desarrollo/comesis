#!/usr/bin/env bash
set -e

# ==============================================================================
# Script de Aprovisionamiento Automático para Ubuntu 26 + PHP 8.5 (COMESIS)
# ==============================================================================

echo "=== 1. Actualizando paquetes del sistema ==="
sudo apt-get update && sudo apt-get upgrade -y
sudo apt-get install -y software-properties-common curl git unzip nginx supervisor mysql-server

echo "=== 2. Configurando repositorio PPA para PHP 8.5 ==="
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update

echo "=== 3. Instalando PHP 8.5 y extensiones requeridas para Laravel ==="
sudo apt-get install -y \
    php8.5-fpm \
    php8.5-cli \
    php8.5-mysql \
    php8.5-mbstring \
    php8.5-xml \
    php8.5-curl \
    php8.5-zip \
    php8.5-gd \
    php8.5-bcmath \
    php8.5-intl \
    php8.5-sqlite3 \
    php8.5-redis

echo "=== 4. Verificando versión de PHP ==="
php8.5 -v

echo "=== 5. Instalando Composer ==="
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
fi
composer --version

echo "=== 6. Configurando permisos de directorios ==="
PROJECT_DIR=$(pwd)
sudo chown -R www-data:www-data "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
sudo chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"

echo "=== 7. Instalando dependencias de producción ==="
composer install --no-dev --optimize-autoloader

echo "=== 8. Optimizando caché de Laravel ==="
php8.5 artisan config:cache
php8.5 artisan route:cache
php8.5 artisan view:cache

echo "=== ¡Aprovisionamiento completado con éxito para Ubuntu 26 y PHP 8.5! ==="
