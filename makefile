.PHONY: install migrate fixtures test-db-create test-migrate

install:
	composer install --no-interaction

migrate:
	php cli-config.php migrations:migrate --no-interaction

fixtures:
	php cli-config.php fixtures:load --no-interaction

deploy: install migrate fixtures