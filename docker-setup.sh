#!/bin/bash

# Script de setup do FlowAssinatura
echo "🚀 Iniciando setup do FlowAssinatura..."

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

# Verificar se o Docker Compose está instalado
if ! command -v docker compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

# Criar arquivo .env se não existir
if [ ! -f .env ]; then
    echo "📝 Criando arquivo .env..."
    cp env.example .env
    echo "✅ Arquivo .env criado com sucesso!"
else
    echo "ℹ️  Arquivo .env já existe."
fi

# Criar diretórios necessários
echo "📁 Criando diretórios necessários..."
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Definir permissões
echo "🔐 Definindo permissões..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Construir e iniciar os containers
echo "🐳 Construindo e iniciando containers..."
docker compose up -d --build

# Aguardar o banco de dados estar pronto
echo "⏳ Aguardando banco de dados..."
sleep 30

# Executar migrações
echo "🗄️  Executando migrações..."
docker compose exec app php artisan migrate --force

# Instalar dependências do Composer
echo "📦 Instalando dependências do Composer..."
docker compose exec app composer install --no-dev --optimize-autoloader

# Gerar chave da aplicação
echo "🔑 Gerando chave da aplicação..."
docker compose exec app php artisan key:generate --force

# Otimizar aplicação
echo "⚡ Otimizando aplicação..."
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# Criar link simbólico para storage
echo "🔗 Criando link simbólico para storage..."
docker compose exec app php artisan storage:link

echo ""
echo "🎉 Setup concluído com sucesso!"
echo ""
echo "📋 Informações de acesso:"
echo "   🌐 Aplicação: http://localhost"
echo "   📧 MailHog: http://localhost:8025"
echo "   🗄️  PHPMyAdmin: http://localhost:8080"
echo "   🔴 Redis Commander: http://localhost:8081"
echo ""
echo "📚 Comandos úteis:"
echo "   docker compose up -d          # Iniciar containers"
echo "   docker compose down           # Parar containers"
echo "   docker compose logs -f app    # Ver logs da aplicação"
echo "   docker compose exec app bash  # Acessar container da aplicação"
echo ""
echo "🔧 Para desenvolvimento:"
echo "   docker compose -f docker-compose.override.yml up -d"
echo "" 