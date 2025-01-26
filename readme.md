
docker compose up --build -d

docker-compose exec php composer install

docker compose up --build -d

docker-compose exec php ./vendor/bin/openapi src -o openapi.yaml