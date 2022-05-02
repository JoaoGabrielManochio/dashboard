docker-compose up -d --build
sleep 5
docker-compose exec php-fpm composer install
docker-compose exec php-fpm chmod -R 777 storage/ vendor/
