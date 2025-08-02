# 🖋️ FlowAssinatura

Sistema open source de assinatura digital de documentos com envio por WhatsApp, gestão via painel e integração por API REST.

---

## 🚀 Como rodar o projeto

### ✅ Pré-requisitos

- Docker + Docker Compose
- Git

### ⚙️ Passos rápidos

1. **Clone o repositório**
   ```bash
   git clone https://github.com/limanetomcz/flowassinatura.git
   cd flowassinatura
Configure o ambiente

bash
Copiar
Editar
cp env.example .env
Suba os containers

bash
Copiar
Editar
docker compose up -d --build
Instale as dependências

bash
Copiar
Editar
docker compose exec app composer install
Gere a chave da aplicação

bash
Copiar
Editar
docker compose exec app php artisan key:generate
Execute as migrações

bash
Copiar
Editar
docker compose exec app php artisan migrate
Crie o link simbólico para o storage

bash
Copiar
Editar
docker compose exec app php artisan storage:link
🌐 Acessos locais
App: http://localhost

Mailhog: http://localhost:8025

PHPMyAdmin (se habilitado): http://localhost:8080

Redis Commander (se habilitado): http://localhost:8081

⚙️ Comandos úteis
bash
Copiar
Editar
# Parar containers
docker compose down

# Logs da aplicação
docker compose logs -f app

# Acessar o bash do container
docker compose exec app bash
📦 Tecnologias principais
Laravel 12 + Livewire

MySQL / Redis

Laravel Sanctum + Telescope

Docker

Z-API / UltraMsg (WhatsApp)

S3 (opcional)

📄 Licença
Este projeto está sob a licença MIT.

yaml
Copiar
Editar

---

### ✅ Vantagens dessa versão

- O foco está totalmente na **instalação e uso imediato**
- O que for extra (Dockerfile, estrutura de pastas, deploy, Telescope) pode ir para o `docs/` ou ser comentado no final
- Reduz o tempo para quem só quer **clonar e rodar**

---