php_docker=api_toubeelib
php_gateway=api_gateway

install:
	make up
	make composer
	make composer_gateway
	make genereDb

up:
	docker compose up -d --remove-orphans --build

composer:
	docker exec -it $(php_docker) composer install

composer_gateway:
	docker exec -it $(php_gateway) composer install

genereDb:
	docker exec -it $(php_docker) php ./src/infrastructure/genereAuthDb.php
	docker exec -it $(php_docker) php ./src/infrastructure/genereDB.php

watchLogs:
	watch -n 2 tail app/var/logs
