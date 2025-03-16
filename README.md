# product-catalog-api

Для того чтобы развернуть приложение необходимо выполнить
```bash
docker-compose up --build
```

далее зайти в контейнер api и выполнить
```bash
make deploy
```
или с хостовой машины
```bash
 docker exec -it php_api make deploy
```
Готово! приложение запущено на `http://localhost:80/`


Запуск тестов из контейнера api
```bash
php ./vendor/bin/phpunit ./tests/
```
или с хостовой машины
```bash
 docker exec -it php_api ./vendor/bin/phpunit ./tests/
```