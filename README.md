# 🖋️ FlowAssinatura

Sistema open source de assinatura digital de documentos. Criado para facilitar o envio, gestão e assinatura de contratos e arquivos PDF, o sistema oferece envio via WhatsApp, integração com ERPs externos via API e total controle das assinaturas.

## 🚀 Funcionalidades

- ✅ Upload de documentos PDF
- ✅ Definição de campos de assinatura
- ✅ Geração de links únicos e seguros
- ✅ Página pública para assinatura
- ✅ Envio de links por WhatsApp e e-mail
- ✅ API REST para integração com ERPs
- ✅ Controle completo por painel administrativo
- ✅ Registro de assinatura com IP, hash, data/hora
- ✅ Multi-tenant (por empresa)
- ✅ Logs e auditoria de ações (Laravel Telescope)
- ✅ Armazenamento local ou em S3

## 🔧 Tecnologias Utilizadas

- **Laravel 11** - Framework PHP
- **Livewire** - Componentes dinâmicos
- **MySQL/PostgreSQL** - Banco de dados
- **Laravel Sanctum** - Autenticação de API
- **Laravel Telescope** - Monitoramento
- **Z-API / UltraMsg** - Envio via WhatsApp
- **dompdf** - Manipulação de PDFs
- **AWS S3** - Armazenamento em nuvem (opcional)
- **Docker** - Containerização

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git

## 🐳 Instalação com Docker

### 1. Clone o repositório

```bash
git clone https://github.com/limanetomcz/flowassinatura.git
cd flowassinatura
```

### 2. Execute o script de setup

```bash
./docker-setup.sh
```

### 3. Ou configure manualmente

#### Copie o arquivo de ambiente

```bash
cp env.example .env
```

#### Configure as variáveis de ambiente

Edite o arquivo `.env` com suas configurações:

```env
# Configurações da Aplicação
APP_NAME="FlowAssinatura"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Configurações do Banco de Dados
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=flowassinatura
DB_USERNAME=flowassinatura
DB_PASSWORD=flowassinatura123
DB_ROOT_PASSWORD=root123

# Configurações do Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Configurações de Email
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@flowassinatura.com"
MAIL_FROM_NAME="${APP_NAME}"

# Configurações do AWS S3 (opcional)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

# Configurações do Z-API (WhatsApp)
ZAPI_TOKEN=
ZAPI_INSTANCE=

# Configurações do UltraMsg (WhatsApp alternativo)
ULTRAMSG_TOKEN=
ULTRAMSG_INSTANCE=
```

#### Inicie os containers

```bash
docker-compose up -d --build
```

#### Execute as migrações

```bash
docker-compose exec app php artisan migrate --force
```

#### Instale as dependências

```bash
docker-compose exec app composer install --no-dev --optimize-autoloader
```

#### Gere a chave da aplicação

```bash
docker-compose exec app php artisan key:generate --force
```

#### Otimize a aplicação

```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

#### Crie o link simbólico para storage

```bash
docker-compose exec app php artisan storage:link
```

## 🌐 Acesso aos Serviços

Após a instalação, você terá acesso aos seguintes serviços:

- **🌐 Aplicação**: http://localhost
- **📧 MailHog** (intercepta emails): http://localhost:8025
- **🗄️ PHPMyAdmin**: http://localhost:8080
- **🔴 Redis Commander**: http://localhost:8081

## 📚 Comandos Úteis

### Gerenciamento de Containers

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app

# Reconstruir containers
docker-compose up -d --build

# Acessar container da aplicação
docker-compose exec app bash
```

### Comandos Laravel

```bash
# Executar migrações
docker-compose exec app php artisan migrate

# Executar seeders
docker-compose exec app php artisan db:seed

# Limpar cache
docker-compose exec app php artisan cache:clear

# Ver rotas
docker-compose exec app php artisan route:list

# Criar controller
docker-compose exec app php artisan make:controller NomeController

# Criar model
docker-compose exec app php artisan make:model NomeModel -m
```

### Comandos Composer

```bash
# Instalar dependências
docker-compose exec app composer install

# Atualizar dependências
docker-compose exec app composer update

# Instalar pacote
docker-compose exec app composer require nome-do-pacote
```

## 🔧 Configurações de Desenvolvimento

### Usando docker-compose.override.yml

Para desenvolvimento, você pode usar o arquivo `docker-compose.override.yml` que inclui ferramentas adicionais:

```bash
docker-compose -f docker-compose.override.yml up -d
```

### Configurações de Produção

Para produção, use o arquivo `docker-compose.prod.yml`:

```bash
docker-compose -f docker-compose.prod.yml up -d
```

## 📁 Estrutura do Projeto

```
flowassinatura/
├── docker/
│   ├── nginx/
│   │   ├── conf.d/
│   │   │   └── default.conf
│   │   └── ssl/
│   ├── php/
│   │   ├── php.ini
│   │   └── opcache.ini
│   ├── mysql/
│   │   └── my.cnf
│   └── supervisor/
│       └── supervisord.conf
├── app/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .dockerignore
├── .env.example
├── docker-compose.yml
├── docker-compose.override.yml
├── docker-compose.prod.yml
├── docker-setup.sh
├── Dockerfile
└── README.md
```

## 🔐 Configurações de Segurança

### SSL/HTTPS

Para configurar SSL em produção:

1. Coloque seus certificados em `docker/nginx/ssl/`
2. Configure o domínio no arquivo `docker/nginx/conf.d/default.conf`
3. Atualize as variáveis de ambiente no `.env`

### Variáveis de Ambiente Sensíveis

Nunca commite o arquivo `.env` no repositório. Use o `.env.example` como template.

## 📊 Monitoramento

### Laravel Telescope

O Laravel Telescope está configurado para monitoramento. Acesse em:

- **Desenvolvimento**: http://localhost/telescope
- **Produção**: Desabilite ou configure autenticação

### Logs

Os logs estão disponíveis em:

```bash
# Logs da aplicação
docker-compose logs -f app

# Logs do Nginx
docker-compose logs -f nginx

# Logs do MySQL
docker-compose logs -f db

# Logs do Redis
docker-compose logs -f redis
```

## 🚀 Deploy em Produção

### 1. Configure as variáveis de ambiente

```bash
cp env.example .env
# Edite o .env com as configurações de produção
```

### 2. Use o docker-compose de produção

```bash
docker-compose -f docker-compose.prod.yml up -d --build
```

### 3. Configure SSL

- Coloque os certificados SSL em `docker/nginx/ssl/`
- Configure o domínio no Nginx

### 4. Configure backup

Configure backup automático do banco de dados e arquivos.

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 🆘 Suporte

Se você encontrar algum problema ou tiver dúvidas:

1. Verifique a [documentação](docs/)
2. Procure por [issues existentes](https://github.com/seu-usuario/flowassinatura/issues)
3. Crie uma nova [issue](https://github.com/seu-usuario/flowassinatura/issues/new)

## 🙏 Agradecimentos

- [Laravel](https://laravel.com/) - Framework PHP
- [Livewire](https://laravel-livewire.com/) - Componentes dinâmicos
- [Docker](https://www.docker.com/) - Containerização
- [Nginx](https://nginx.org/) - Servidor web
- [MySQL](https://www.mysql.com/) - Banco de dados
- [Redis](https://redis.io/) - Cache e sessões
