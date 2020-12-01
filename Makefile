.PHONY: up shell

up: vendor
	docker-compose up --build --detach --force-recreate --remove-orphans

vendor: composer.json composer.lock
	docker run -v $(PWD):/app composer:2.0 install
	touch vendor

shell: up
	docker-compose run php sh