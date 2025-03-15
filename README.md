# product-catalog-api


Миграции
```bash
php ./vendor/bin/doctrine-migrations migrations:diff
php ./vendor/bin/doctrine-migrations migrations:migrate
```

Фикстуры
```bash
php ./vendor/bin/doctrine-migrations fixtures:load
```