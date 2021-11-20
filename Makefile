default: runtests

runtests:
	./bin/phpunit --color --stop-on-failure

agile:
	./bin/phpunit --testdox --color

cover:
	./bin/phpunit --coverage-html coverage

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

bash_php:
	docker-compose exec php bash
