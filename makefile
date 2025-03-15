.PHONY: install migrate fixtures test-db-create test-migrate

install:
	composer install --no-interaction

migrate:
	php ./vendor/bin/doctrine-migrations migrations:migrate

fixtures:
	php ./vendor/bin/doctrine-migrations fixtures:load

deploy: install migrate fixtures