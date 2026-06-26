# Gestão Condomínio API

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Slim](https://img.shields.io/badge/Slim-4-74a045?style=for-the-badge)
![Doctrine](https://img.shields.io/badge/Doctrine-ORM%20%2B%20Migrations-fc6a31?style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-latest-DC382D?style=for-the-badge&logo=redis&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white)

API do **Gestor de Condomínios**, responsável por concentrar as regras de negócio, persistência de dados, autenticação e integrações do sistema. Ela fornece uma base confiável para administrar informações de condomínios com organização, segurança e escalabilidade.

Construída com PHP, Slim Framework, Doctrine ORM, Doctrine Migrations, MySQL, Redis, JWT e OpenAPI, a API foi pensada para separar bem responsabilidades e facilitar a evolução dos módulos do produto.

## O que esta API entrega

- Cadastro e consulta de pessoas, propriedades e veículos.
- Gestão financeira com contas a pagar e contas a receber.
- Registro de ocorrências para acompanhamento administrativo.
- Publicação de informativos com controle de visibilidade.
- Autenticação com token JWT.
- Migrations versionadas para evolução segura do banco.
- Fixtures e dados fictícios para acelerar testes e desenvolvimento.
- Documentação OpenAPI/Swagger para facilitar consumo e integração.

## Módulos

- Autenticação
- Pessoas
- Propriedades
- Veículos
- Contas a pagar
- Contas a receber
- Informativos
- Ocorrências
- Cidades e estados

## Preview do sistema

Embora este repositório seja a API, os prints abaixo mostram as telas que ela alimenta no painel web.

### Dashboard

![Dashboard do Gestor de Condomínios](assets/01.Dashboard.png)

### Gestão de Pessoas

![Tela de Gestão de Pessoas](assets/02.Gest%C3%A3o%20de%20Pessoas.png)

### Gestão de Propriedades

![Tela de Gestão de Propriedades](assets/03.Gest%C3%A3o%20de%20Propriedades.png)

## Requisitos

- Docker
- Docker Compose

## Configuração

Crie o arquivo `.env` a partir do exemplo:

```powershell
copy .env.example .env
```

Configure as variáveis principais:

```env
DB_NAME=gestor_condominios
DB_USER=gestor
DB_PASSWORD=gestor
DB_HOST=mysql
API_JWT_KEY=123456789
```

## Instalação

Suba os containers:

```powershell
docker compose up --build -d
```

Instale as dependências PHP:

```powershell
docker compose exec php composer install
```

Rode as migrations:

```powershell
docker compose exec php vendor/bin/doctrine-migrations migrate --configuration=config/migrations.php --db-configuration=config/migrations-db.php
```

Carregue os dados iniciais:

```powershell
docker compose exec php php src/Database/seed.php
```

Opcionalmente, gere dados fictícios:

```powershell
docker compose exec php php src/Database/faker.php
```

## Seeds e dados de demonstração

Use o seed para cadastrar dados essenciais do sistema, incluindo registros-base usados pelos módulos:

```powershell
docker compose exec php php src/Database/seed.php
```

Use o faker para popular o ambiente com dados fictícios e facilitar testes, navegação pelas telas e apresentações do produto:

```powershell
docker compose exec php php src/Database/faker.php
```

## URLs

- API: `http://localhost:8080`
- Swagger UI: `http://localhost:8080/swagger`
- OpenAPI YAML: `http://localhost:8080/openapi.yaml`

## Usuário inicial

```text
CPF: 00000000000
Senha: admin
```

Também é possível gerar o usuário administrador pela rota:

```text
GET /api/gerar/adm
```

## Migrations

Ver status:

```powershell
docker compose exec php vendor/bin/doctrine-migrations status --configuration=config/migrations.php --db-configuration=config/migrations-db.php
```

Gerar nova migration:

```powershell
docker compose exec php vendor/bin/doctrine-migrations generate --configuration=config/migrations.php --db-configuration=config/migrations-db.php
```

Executar migrations:

```powershell
docker compose exec php vendor/bin/doctrine-migrations migrate --configuration=config/migrations.php --db-configuration=config/migrations-db.php
```

## OpenAPI

Gerar ou atualizar a documentação:

```powershell
docker compose exec php ./vendor/bin/openapi src -o public/openapi.yaml
```

## Comandos úteis

```powershell
docker compose ps
docker compose logs -f php
docker compose logs -f nginx
docker compose logs -f mysql
docker compose down
```

## Estrutura

```text
app/
+-- config/       # Rotas, container, middlewares e migrations
+-- migrations/   # Migrations Doctrine
+-- public/       # index.php, Swagger e openapi.yaml
`-- src/
    +-- Controller/
    +-- Database/
    +-- Entity/
    +-- Middleware/
    +-- Security/
    `-- Service/
```
