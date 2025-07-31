# 🐳 Configuração Docker - FlowAssinatura

## 📁 Estrutura de Arquivos Criada

```
flowassinatura/
├── docker/
│   ├── nginx/
│   │   ├── conf.d/
│   │   │   └── default.conf          # Configuração do Nginx
│   │   └── ssl/                      # Certificados SSL
│   ├── php/
│   │   ├── php.ini                   # Configuração PHP
│   │   └── opcache.ini               # Configuração OPcache
│   ├── mysql/
│   │   └── my.cnf                    # Configuração MySQL
│   └── supervisor/
│       └── supervisord.conf          # Configuração Supervisor
├── .vscode/
│   ├── settings.json                 # Configurações VSCode
│   └── extensions.json               # Extensões recomendadas
├── .dockerignore                     # Arquivos ignorados no Docker
├── .gitignore                        # Arquivos ignorados no Git
├── docker-compose.yml                # Configuração principal
├── docker-compose.override.yml       # Configuração desenvolvimento
├── docker-compose.prod.yml           # Configuração produção
├── docker-setup.sh                   # Script de setup
├── Dockerfile                        # Imagem da aplicação
├── env.example                       # Variáveis de ambiente
├── Makefile                          # Comandos úteis
└── README.md                         # Documentação principal
```

## 🚀 Serviços Configurados

### 1. **Aplicação Laravel** (`app`)

- PHP 8.2-FPM
- Extensões: PDO, MySQL, Redis, Imagick, GD, ZIP, etc.
- Composer instalado
- Supervisor para gerenciar processos

### 2. **Nginx** (`nginx`)

- Servidor web reverso
- Configuração SSL/HTTPS
- Otimizações de cache
- Headers de segurança

### 3. **MySQL 8.0** (`db`)

- Banco de dados principal
- Configurações otimizadas
- Volumes persistentes

### 4. **Redis** (`redis`)

- Cache e sessões
- Queue processing

### 5. **MailHog** (`mailhog`)

- Intercepta emails em desenvolvimento
- Interface web para visualização

### 6. **PHPMyAdmin** (`phpmyadmin`)

- Interface web para MySQL
- Disponível em desenvolvimento

### 7. **Redis Commander** (`redis-commander`)

- Interface web para Redis
- Disponível em desenvolvimento

### 8. **Queue Worker** (`queue`)

- Processamento em background
- Múltiplas instâncias

### 9. **Scheduler** (`scheduler`)

- Tarefas agendadas
- Cron jobs

## 🔧 Comandos Principais

### Setup Inicial

```bash
# Setup automático
./docker-setup.sh

# Setup manual
cp env.example .env
docker-compose up -d --build
```

### Gerenciamento

```bash
# Usando Makefile
make help                    # Ver todos os comandos
make install                 # Instalação completa
make start                   # Iniciar containers
make stop                    # Parar containers
make logs                    # Ver logs
make shell                   # Acessar container

# Usando Docker Compose
docker-compose up -d         # Iniciar
docker-compose down          # Parar
docker-compose logs -f app   # Logs da aplicação
docker-compose exec app bash # Shell da aplicação
```

### Desenvolvimento vs Produção

```bash
# Desenvolvimento (com ferramentas extras)
docker-compose -f docker-compose.override.yml up -d

# Produção (otimizado)
docker-compose -f docker-compose.prod.yml up -d
```

## 🌐 Portas e URLs

| Serviço           | Porta | URL                   |
| ----------------- | ----- | --------------------- |
| Aplicação         | 80    | http://localhost      |
| Aplicação (HTTPS) | 443   | https://localhost     |
| MailHog           | 8025  | http://localhost:8025 |
| PHPMyAdmin        | 8080  | http://localhost:8080 |
| Redis Commander   | 8081  | http://localhost:8081 |
| MySQL             | 3306  | localhost:3306        |
| Redis             | 6379  | localhost:6379        |

## 🔐 Configurações de Segurança

### SSL/HTTPS

1. Coloque certificados em `docker/nginx/ssl/`
2. Configure domínio no Nginx
3. Atualize `.env`

### Variáveis Sensíveis

- Nunca commite `.env`
- Use `env.example` como template
- Configure secrets em produção

## 📊 Monitoramento

### Laravel Telescope

- Desenvolvimento: http://localhost/telescope
- Produção: Configure autenticação

### Logs

```bash
# Logs da aplicação
docker-compose logs -f app

# Logs do Nginx
docker-compose logs -f nginx

# Logs do MySQL
docker-compose logs -f db
```

## 🚀 Deploy em Produção

### 1. Configure ambiente

```bash
cp env.example .env
# Edite .env com configurações de produção
```

### 2. Use configuração de produção

```bash
docker-compose -f docker-compose.prod.yml up -d --build
```

### 3. Configure SSL

- Certificados em `docker/nginx/ssl/`
- Domínio no Nginx

### 4. Configure backup

- Banco de dados
- Arquivos de upload

## 🔧 Personalizações

### Adicionar Novos Serviços

1. Adicione no `docker-compose.yml`
2. Configure no `docker-compose.override.yml` se necessário
3. Documente no README

### Modificar Configurações

- PHP: `docker/php/php.ini`
- Nginx: `docker/nginx/conf.d/default.conf`
- MySQL: `docker/mysql/my.cnf`
- Supervisor: `docker/supervisor/supervisord.conf`

## 📝 Notas Importantes

1. **Volumes**: Dados persistentes em `dbdata` e `redisdata`
2. **Networks**: Rede isolada `flowassinatura_network`
3. **Health Checks**: Endpoint `/health` configurado
4. **Performance**: OPcache e Redis configurados
5. **Segurança**: Headers de segurança no Nginx

## 🆘 Troubleshooting

### Problemas Comuns

1. **Porta já em uso**

   ```bash
   # Verificar portas
   netstat -tulpn | grep :80
   # Parar serviço conflitante
   sudo systemctl stop apache2
   ```

2. **Permissões de arquivos**

   ```bash
   # Corrigir permissões
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

3. **Banco não conecta**

   ```bash
   # Verificar logs
   docker-compose logs db
   # Aguardar inicialização
   sleep 30
   ```

4. **Cache não funciona**
   ```bash
   # Limpar cache
   make cache-clear
   # Reconstruir containers
   make build
   ```

## 📚 Recursos Adicionais

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Documentation](https://laravel.com/docs)
- [Nginx Documentation](https://nginx.org/en/docs/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Redis Documentation](https://redis.io/documentation)
