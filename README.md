docker-compose up --build
docker exec -it tp_adictiz-web-1 bash

php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate


