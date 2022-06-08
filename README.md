![linter](https://github.com/yulia633/blog-php/workflows/linter/badge.svg)

### О коде

Разработка блога **php**.

### Docker

Вы можете развернуть этот проект с помощью **docker** и **docker-compose**:

**Команды:**

```bash
# Запустить - это псевдоним для docker-compose up -d --build.
$ make up

# Проверить.
$ http://localhost:80

# Остановить контейнеры, псевдоним для docker-compose down.
$ make down

# Зайти в контейнер в базу, псевдоним для docker exec -it.
$ make docker-compose-bash-mysql
```
