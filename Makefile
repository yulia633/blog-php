install:
	composer install

test:
	composer exec phpunit tests

lint:
	composer exec phpcs -v -- --standard=PSR12 src public -np

test-coverage:
	composer exec --verbose phpunit -- --testsuite gh-actions --coverage-clover build/logs/clover.xml

up:
	docker-compose up -d
	make docker-install

down:
	docker-compose down

docker-install:
	docker exec -it application composer install

docker-compose-test:
	docker exec -it application make test

docker-compose-bash:
	docker exec -it application bash

docker-compose-bash-mysql:
	docker exec -it database bash

env-prepare:
	cp -n .env.example .env || true
