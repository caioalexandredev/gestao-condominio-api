
docker compose up --build -d

docker-compose exec php composer install

docker compose up --build -d

docker-compose exec php ./vendor/bin/openapi src -o public/openapi.yaml

docker-compose exec php vendor/bin/doctrine-migrations generate  --configuration=config/migrations.php --db-configuration=config/migrations-db.php

docker-compose exec php vendor/bin/doctrine-migrations migrate  --configuration=config/migrations.php --db-configuration=config/migrations-db.php

docker-compose exec php php src/Database/seed.php

docker-compose exec php php src/Database/faker.php