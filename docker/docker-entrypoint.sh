#!/bin/sh
set -e

# Executar instalação de dependências do composer (caso não estejam montadas)
if [ ! -d "vendor" ]; then
    echo "Instalando dependências do Composer para produção..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# Aguardar o banco de dados estar pronto (se necessário)
echo "Aguardando inicialização da aplicação..."

# Rodar as migrations automaticamente em produção de forma segura
echo "Executando migrations do banco de dados..."
php artisan migrate --force

# Otimizar carregamento do Laravel cacheando configurações e rotas
echo "Cacheando configurações, rotas e views para alta performance..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Executar o comando original passado no CMD (normalmente php-fpm)
exec "$@"
