![linter](https://github.com/yulia633/blog-php/workflows/linter/badge.svg)

### О коде

Разработка блога на **php** и **mysql**.

### Docker

Вы можете развернуть этот проект с помощью **docker** и **docker-compose**.

### Установка  

1. Склонируйте репозиторий:

```bash
$ git clone https://github.com/yulia633/blog-php.git  
$ cd blog_php
```

2. Подготовьте файл `.env`:

```bash
$ make env-prepare  
```

3. Измените параметры БД и сервера в файле `.env`:

```bash
...
MYSQL_USER='user'
MYSQL_HOST='mariadb'
APACHE_DEFAULT_PORT='80'
MYSQL_PASSWORD='testpassword'
...
```

**Команды:**

```bash
# Запустить - это псевдоним для docker-compose up -d --build.
$ make up

# Проверить приложение (будет доступно) по http://localhost.
$ http://localhost:80

# Остановить контейнеры, псевдоним для docker-compose down.
$ make down

# Зайти в контейнер в базу, псевдоним для docker exec -it.
$ make docker-compose-bash-mysql

# Зайти в контейнер в приложение, псевдоним для docker exec -it.
$ make docker-compose-bash

# Запустить codesniffer.
$ make lint
```

### Используемые зависимости:
- Slim
- Twig Template Engine
- ext-pdo
- vlucas/phpdotenv
- squizlabs/php_codesniffer
