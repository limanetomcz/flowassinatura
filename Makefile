# Makefile para FlowAssinatura
.PHONY: help install start stop restart logs build clean test migrate seed artisan composer

# Variáveis
COMPOSE = docker-compose
COMPOSE_DEV = docker-compose -f docker-compose.override.yml
COMPOSE_PROD = docker-compose -f docker-compose.prod.yml

# Comando padrão
help: ## Mostra esta ajuda
	@echo "Comandos disponíveis:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Instalação e Setup
install: ## Instala o projeto completo
	@echo "🚀 Instalando FlowAssinatura..."
	@if [ ! -f .env ]; then cp env.example .env; fi
	@mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
	@chmod -R 755 storage bootstrap/cache
	@$(COMPOSE) up -d --build
	@echo "⏳ Aguardando banco de dados..."
	@sleep 30
	@$(COMPOSE) exec app php artisan migrate --force
	@$(COMPOSE) exec app composer install --no-dev --optimize-autoloader
	@$(COMPOSE) exec app php artisan key:generate --force
	@$(COMPOSE) exec app php artisan config:cache
	@$(COMPOSE) exec app php artisan route:cache
	@$(COMPOSE) exec app php artisan view:cache
	@$(COMPOSE) exec app php artisan storage:link
	@echo "✅ Instalação concluída!"

setup-dev: ## Configura ambiente de desenvolvimento
	@echo "🔧 Configurando ambiente de desenvolvimento..."
	@$(COMPOSE_DEV) up -d --build
	@echo "✅ Ambiente de desenvolvimento configurado!"

setup-prod: ## Configura ambiente de produção
	@echo "🚀 Configurando ambiente de produção..."
	@$(COMPOSE_PROD) up -d --build
	@echo "✅ Ambiente de produção configurado!"

# Gerenciamento de Containers
start: ## Inicia os containers
	@echo "🐳 Iniciando containers..."
	@$(COMPOSE) up -d

stop: ## Para os containers
	@echo "🛑 Parando containers..."
	@$(COMPOSE) down

restart: ## Reinicia os containers
	@echo "🔄 Reiniciando containers..."
	@$(COMPOSE) restart

logs: ## Mostra logs da aplicação
	@$(COMPOSE) logs -f app

logs-all: ## Mostra logs de todos os serviços
	@$(COMPOSE) logs -f

# Build e Manutenção
build: ## Reconstrói os containers
	@echo "🔨 Reconstruindo containers..."
	@$(COMPOSE) up -d --build

clean: ## Limpa containers e volumes
	@echo "🧹 Limpando containers e volumes..."
	@$(COMPOSE) down -v --remove-orphans
	@docker system prune -f

# Banco de Dados
migrate: ## Executa migrações
	@echo "🗄️ Executando migrações..."
	@$(COMPOSE) exec app php artisan migrate

migrate-fresh: ## Recria o banco de dados
	@echo "🔄 Recriando banco de dados..."
	@$(COMPOSE) exec app php artisan migrate:fresh

seed: ## Executa seeders
	@echo "🌱 Executando seeders..."
	@$(COMPOSE) exec app php artisan db:seed

# Comandos Laravel
artisan: ## Executa comando artisan (uso: make artisan cmd="migrate")
	@$(COMPOSE) exec app php artisan $(cmd)

cache-clear: ## Limpa cache da aplicação
	@echo "🧹 Limpando cache..."
	@$(COMPOSE) exec app php artisan cache:clear
	@$(COMPOSE) exec app php artisan config:clear
	@$(COMPOSE) exec app php artisan route:clear
	@$(COMPOSE) exec app php artisan view:clear

cache-optimize: ## Otimiza cache da aplicação
	@echo "⚡ Otimizando cache..."
	@$(COMPOSE) exec app php artisan config:cache
	@$(COMPOSE) exec app php artisan route:cache
	@$(COMPOSE) exec app php artisan view:cache

# Comandos Composer
composer: ## Executa comando composer (uso: make composer cmd="install")
	@$(COMPOSE) exec app composer $(cmd)

composer-install: ## Instala dependências do Composer
	@echo "📦 Instalando dependências..."
	@$(COMPOSE) exec app composer install

composer-update: ## Atualiza dependências do Composer
	@echo "🔄 Atualizando dependências..."
	@$(COMPOSE) exec app composer update

# Testes
test: ## Executa testes
	@echo "🧪 Executando testes..."
	@$(COMPOSE) exec app php artisan test

test-coverage: ## Executa testes com cobertura
	@echo "📊 Executando testes com cobertura..."
	@$(COMPOSE) exec app php artisan test --coverage

# Acesso aos Serviços
shell: ## Acessa shell do container da aplicação
	@$(COMPOSE) exec app bash

mysql: ## Acessa MySQL
	@$(COMPOSE) exec db mysql -u flowassinatura -pflowassinatura123 flowassinatura

redis: ## Acessa Redis CLI
	@$(COMPOSE) exec redis redis-cli

# Monitoramento
status: ## Mostra status dos containers
	@$(COMPOSE) ps

health: ## Verifica saúde dos serviços
	@echo "🏥 Verificando saúde dos serviços..."
	@curl -f http://localhost/health || echo "❌ Aplicação não está respondendo"
	@curl -f http://localhost:8025 || echo "❌ MailHog não está respondendo"
	@curl -f http://localhost:8080 || echo "❌ PHPMyAdmin não está respondendo"
	@curl -f http://localhost:8081 || echo "❌ Redis Commander não está respondendo"

# Backup e Restore
backup: ## Faz backup do banco de dados
	@echo "💾 Fazendo backup do banco de dados..."
	@$(COMPOSE) exec db mysqldump -u flowassinatura -pflowassinatura123 flowassinatura > backup_$(shell date +%Y%m%d_%H%M%S).sql

restore: ## Restaura backup do banco de dados (uso: make restore file=backup.sql)
	@echo "📥 Restaurando backup..."
	@$(COMPOSE) exec -T db mysql -u flowassinatura -pflowassinatura123 flowassinatura < $(file)

# Utilitários
logs-tail: ## Mostra últimas linhas dos logs
	@$(COMPOSE) logs --tail=100 app

logs-error: ## Mostra apenas logs de erro
	@$(COMPOSE) logs app | grep -i error

info: ## Mostra informações do sistema
	@echo "📋 Informações do FlowAssinatura:"
	@echo "   🌐 Aplicação: http://localhost"
	@echo "   📧 MailHog: http://localhost:8025"
	@echo "   🗄️  PHPMyAdmin: http://localhost:8080"
	@echo "   🔴 Redis Commander: http://localhost:8081"
	@echo "   📊 Telescope: http://localhost/telescope" 